<?php

declare(strict_types=1);

use Catch\Enums\Code;
use Catch\Exceptions\FailedException;
use Catch\Facade\Admin;
use Catch\Support\Macros\Blueprint as CatchBlueprint;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\BootProviders;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\TestResponse;
use Modules\System\Middlewares\McpMiddleware;
use Modules\System\Models\McpToken;
use Modules\System\Models\McpTokenLog;
use Modules\User\Models\User;
use Tests\TestCase;

abstract class McpTokensTestCase extends TestCase
{
    public function createApplication(): Application
    {
        $app = require dirname(__DIR__, 2) . '/bootstrap/app.php';
        $this->traitsUsedByTest = class_uses_recursive(static::class);

        // Providers may query configuration during boot: isolate before any provider registers.
        $app->afterBootstrapping(LoadConfiguration::class, function (Application $app): void {
            $app->make('config')->set([
                'database.default' => 'sqlite',
                'database.connections' => [
                    'sqlite' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true],
                ],
                'cache.default' => 'array',
                'session.driver' => 'array',
                'logging.default' => 'null',
                'catch.listen_db_log' => false,
                'catch.super_admin' => [],
                'app.debug' => true,
            ]);
        });

        $app->beforeBootstrapping(BootProviders::class, function (): void {
            $this->assertSame('sqlite', DB::connection()->getDriverName());
            $this->assertSame(':memory:', DB::connection()->getDatabaseName());
            (new CatchBlueprint())->boot();

            foreach ([
                'modules/User/database/migrations/2022_12_04_060250_create_users.php',
                'modules/User/database/migrations/2022_12_17_031519_create_log_operate.php',
                'modules/Permissions/database/migrations/2022_12_05_084442_create_roles.php',
                'modules/Permissions/database/migrations/2022_12_06_110551_create_jobs.php',
                'modules/Permissions/database/migrations/2022_12_07_103318_create_permissions.php',
                'modules/Permissions/database/migrations/2022_12_10_061840_create_user_has_roles.php',
                'modules/Permissions/database/migrations/2022_12_10_061919_create_user_has_jobs.php',
                'modules/Permissions/database/migrations/2022_12_10_061857_create_role_has_permissions.php',
                'modules/System/database/migrations/2023_05_08_043214_create_system_dictionary.php',
                'modules/System/database/migrations/2023_05_08_043740_create_system_dictionary_values.php',
                'vendor/laravel/sanctum/database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php',
                'modules/System/database/migrations/2026_10_09_221523_create_system_mcp_tokens.php',
                'modules/System/database/migrations/2026_10_12_210939_create_system_mcp_token_logs.php',
            ] as $migration) {
                (require base_path($migration))->up();
            }
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function owner(string $username = 'mcp-owner'): User
    {
        return User::query()->create([
            'username' => $username,
            'email' => $username . '@example.test',
            'password' => 'test-password',
            'status' => 1,
        ])->refresh();
    }

    protected function rpc(string $token, string $method = 'ping', array $params = [], array $headers = []): TestResponse
    {
        Auth::forgetGuards();

        return $this->postJson('/ai/mcp', [
            'jsonrpc' => '2.0', 'id' => 1, 'method' => $method, 'params' => $params,
        ], $headers + ['Authorization' => 'Bearer ' . $token, 'Accept' => 'application/json, text/event-stream']);
    }

    protected function grant(User $user, string $mark): int
    {
        $roleId = DB::table('roles')->insertGetId(['role_name' => 'MCP reader', 'data_range' => 1]);
        $permissionId = DB::table('permissions')->insertGetId([
            'permission_name' => $mark, 'module' => 'system', 'permission_mark' => $mark,
            'route' => '', 'icon' => '', 'component' => '', 'type' => 3,
        ]);
        DB::table('user_has_roles')->insert(['user_id' => $user->id, 'role_id' => $roleId]);
        DB::table('role_has_permissions')->insert(['role_id' => $roleId, 'permission_id' => $permissionId]);

        return $permissionId;
    }
}

uses(McpTokensTestCase::class);

test('MCP credentials use an independent hash-only table and framework listing', function (): void {
    $owner = $this->owner();
    $other = $this->owner('other-owner');
    $model = new McpToken();
    $issued = $model->issueFor($owner, 'Desktop client', time() + 3600);
    $otherIssued = $model->issueFor($other, 'Other client', time() + 3600);
    $stored = McpToken::query()->findOrFail($issued['id']);

    expect($issued['token'])->toMatch('/\A[a-f0-9]{64}\z/')
        ->and($stored->user_id)->toBe($owner->id)
        ->and($stored->token_hash)->toBe(hash('sha256', $issued['token']))
        ->and($stored->toArray())->not->toHaveKeys(['token', 'token_hash', 'abilities', 'purpose'])
        ->and($model->getList()->getCollection()->pluck('id')->all())->toBe([$otherIssued['id'], $issued['id']])
        ->and(Schema::hasColumn('system_mcp_tokens', 'abilities'))->toBeFalse()
        ->and(DB::table('personal_access_tokens')->count())->toBe(0);
});

test('management actions require the framework menu permission', function (string $action, string $method, string $suffix): void {
    $model = new McpToken();
    $owner = $this->owner();
    $issued = $model->issueFor($owner, 'Managed client', time() + 3600);
    $operator = $this->owner('token-operator');
    $login = $operator->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;
    $headers = ['Authorization' => 'Bearer ' . $login, 'Request-from' => 'Dashboard'];
    $endpoint = '/' . config('catch.route.prefix') . '/system/mcp/tokens/' . $issued['id'] . $suffix;
    $invoke = fn () => $this->json($method, $endpoint, [], $headers);

    $invoke()->assertJsonPath('code', Code::PERMISSION_FORBIDDEN->value());
    expect(McpToken::query()->findOrFail($issued['id'])->revoked_at)->toBe(0);
    $this->grant($operator, 'mcpTokens@' . $action);
    $response = $invoke();
    $response->assertSuccessful()->assertJsonPath('code', Code::SUCCESS->value());
    if ($action === 'rotate') {
        $stored = McpToken::query()->findOrFail($issued['id']);
        expect($response->json('data.id'))->toBe($issued['id'])
            ->and($stored->user_id)->toBe($owner->id)
            ->and($stored->revoked_at)->toBe(0)
            ->and($stored->token_hash)->toBe(hash('sha256', $response->json('data.token')));
    } else {
        expect(McpToken::query()->findOrFail($issued['id'])->revoked_at)->toBeGreaterThan(0);
    }
})->with(['revoke' => ['destroy', 'DELETE', ''], 'rotate' => ['rotate', 'POST', '/rotate']]);

test('rotation and revocation invalidate the old credential on the next HTTP request', function (): void {
    $owner = $this->owner();
    $model = new McpToken();
    $original = $model->issueFor($owner, 'Rotating client', time() + 3600);
    $this->rpc($original['token'])->assertSuccessful()->assertJsonPath('jsonrpc', '2.0');

    $replacement = $model->rotate($original['id']);
    expect($replacement['token'])->not->toBe($original['token'])
        ->and($replacement['id'])->toBe($original['id'])
        ->and($replacement['expires_at'])->toBe($original['expires_at']);
    $this->rpc($original['token'])->assertUnauthorized();
    $this->rpc($replacement['token'])->assertSuccessful();
    expect($model->revoke($replacement['id']))->toBeTrue();
    $this->rpc($replacement['token'])->assertUnauthorized();
    expect(fn () => $model->rotate($replacement['id']))->toThrow(FailedException::class);
    expect($model->revoke($replacement['id']))->toBeTrue();
});

test('MCP authentication rejects unusable credentials and users', function (string $failure): void {
    $owner = $this->owner();
    $issued = (new McpToken())->issueFor($owner, 'Rejected client', time() + 3600);
    $token = $issued['token'];

    match ($failure) {
        'expired' => DB::table('system_mcp_tokens')->where('id', $issued['id'])->update(['expires_at' => time()]),
        'disabled' => DB::table('system_mcp_tokens')->where('id', $issued['id'])->update(['status' => 2]),
        'revoked' => DB::table('system_mcp_tokens')->where('id', $issued['id'])->update(['revoked_at' => time()]),
        'deleted' => DB::table('system_mcp_tokens')->where('id', $issued['id'])->update(['deleted_at' => time()]),
        'user-disabled' => DB::table('admin_users')->where('id', $owner->id)->update(['status' => 2]),
        'user-invalid-status' => DB::table('admin_users')->where('id', $owner->id)->update(['status' => 0]),
        'user-deleted' => DB::table('admin_users')->where('id', $owner->id)->update(['deleted_at' => time()]),
        'malformed' => $token = 'bad-token',
        'unknown' => $token = str_repeat('0', 64),
        'missing' => $token = '',
    };

    $this->rpc($token, headers: ['Request-from' => 'Dashboard'])
        ->assertUnauthorized()
        ->assertHeader('Content-Type', 'application/json')
        ->assertHeader('WWW-Authenticate')
        ->assertJsonPath('message', 'Unauthenticated.');
})->with(['expired', 'disabled', 'revoked', 'deleted', 'user-disabled', 'user-invalid-status', 'user-deleted', 'malformed', 'unknown', 'missing']);

test('HTTP MCP returns ordinary JSON-RPC despite the dashboard header', function (): void {
    $issued = (new McpToken())->issueFor($this->owner(), 'HTTP client', time() + 3600);

    $response = $this->rpc($issued['token'], 'initialize', [
        'protocolVersion' => '2025-11-25', 'capabilities' => (object) [],
        'clientInfo' => ['name' => 'pest', 'version' => '1.0'],
    ], ['Request-from' => 'Dashboard']);

    $response->assertSuccessful()->assertHeader('Content-Type', 'application/json')
        ->assertJsonPath('jsonrpc', '2.0')->assertJsonPath('id', 1)
        ->assertJsonStructure(['result' => ['protocolVersion', 'serverInfo' => ['name', 'version']]]);
    expect($response->json())->not->toHaveKeys(['code', 'message', 'data'])
        ->and(McpToken::query()->findOrFail($issued['id'])->last_used_at)->toBeGreaterThan(0);
    $this->get('/ai/mcp')->assertStatus(405)->assertHeader('Allow', 'POST');
});

test('MCP and backend login credentials are isolated in both directions', function (): void {
    $owner = $this->owner();
    $mcp = (new McpToken())->issueFor($owner, 'MCP client', time() + 3600);
    $login = $owner->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;

    $this->rpc($login)->assertUnauthorized();
    $this->rpc($mcp['token'], 'tools/call', ['name' => 'current-user'])->assertSuccessful();
    $this->getJson('/' . config('catch.route.prefix') . '/system/mcp/tokens', [
        'Authorization' => 'Bearer ' . $mcp['token'],
    ])->assertJsonPath('code', Code::LOST_LOGIN->value());
    expect(DB::table('personal_access_tokens')->count())->toBe(1)
        ->and(McpToken::query()->count())->toBe(1);
});

test('the management API validates input and binds credentials to an authorized selected user', function (): void {
    $owner = $this->owner();
    $other = $this->owner('other-owner');
    $disabled = $this->owner('disabled-owner');
    $deleted = $this->owner('deleted-owner');
    DB::table('admin_users')->where('id', $disabled->id)->update(['status' => 2]);
    DB::table('admin_users')->where('id', $deleted->id)->update(['deleted_at' => time()]);
    $this->grant($owner, 'mcpTokens@store');
    $login = $owner->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;
    $headers = ['Authorization' => 'Bearer ' . $login, 'Request-from' => 'Dashboard'];
    $endpoint = '/' . config('catch.route.prefix') . '/system/mcp/tokens';

    foreach ([999999, $disabled->id, $deleted->id] as $invalidUserId) {
        $this->postJson($endpoint, ['name' => 'Client', 'expires_at' => time() + 3600, 'user_id' => $invalidUserId], $headers)
            ->assertUnprocessable()->assertJsonValidationErrors('user_id');
    }
    $this->postJson($endpoint, ['name' => str_repeat('a', 101), 'expires_at' => time(), 'user_id' => $owner->id], $headers)
        ->assertUnprocessable()->assertJsonValidationErrors(['name', 'expires_at']);
    $this->postJson($endpoint, ['name' => 'Client', 'expires_at' => time() + 3600], $headers)
        ->assertUnprocessable()->assertJsonValidationErrors('user_id');
    expect(McpToken::query()->count())->toBe(0);

    $response = $this->postJson($endpoint, ['name' => 'Client', 'expires_at' => time() + 3600, 'user_id' => $other->id], $headers);
    $response->assertSuccessful()->assertJsonPath('code', Code::SUCCESS->value());
    $stored = McpToken::query()->findOrFail($response->json('data.id'));
    expect($stored->user_id)->toBe($other->id)
        ->and($stored->token_hash)->toBe(hash('sha256', $response->json('data.token')))
        ->and($response->json('data.endpoint'))->toEndWith('/ai/mcp');
});

test('an administrator selects a backend user and rotation preserves the selected identity', function (): void {
    $operator = $this->owner('token-administrator');
    $selected = $this->owner('selected-user');
    config(['catch.super_admin' => [$operator->id]]);
    $login = $operator->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;
    $headers = ['Authorization' => 'Bearer ' . $login, 'Request-from' => 'Dashboard'];
    $endpoint = '/' . config('catch.route.prefix') . '/system/mcp/tokens';

    $this->getJson('/' . config('catch.route.prefix') . '/users?status=1&username=selected', $headers)
        ->assertSuccessful()->assertJsonPath('data.0.id', $selected->id);
    $response = $this->postJson($endpoint, [
        'name' => 'Selected user client', 'user_id' => $selected->id, 'expires_at' => time() + 3600,
    ], $headers);
    $response->assertSuccessful();
    $stored = McpToken::query()->findOrFail($response->json('data.id'));
    expect($stored->user_id)->toBe($selected->id);

    $this->getJson($endpoint, $headers)
        ->assertSuccessful()->assertJsonPath('data.0.user.username', $selected->username);
    $rotated = $this->postJson($endpoint . '/' . $stored->id . '/rotate', [], $headers);
    $rotated->assertSuccessful()->assertJsonPath('code', Code::SUCCESS->value());
    $replacement = $rotated->json('data');
    expect(McpToken::query()->findOrFail($replacement['id'])->user_id)->toBe($selected->id);
    $identity = $this->rpc($replacement['token'], 'tools/call', ['name' => 'current-user']);
    $identity->assertSuccessful();
    expect(json_decode($identity->json('result.content.0.text'), true, flags: JSON_THROW_ON_ERROR)['id'])->toBe($selected->id);
    $this->deleteJson($endpoint . '/' . $replacement['id'], [], $headers)
        ->assertSuccessful()->assertJsonPath('code', Code::SUCCESS->value());
    expect(McpToken::query()->findOrFail($replacement['id'])->revoked_at)->toBeGreaterThan(0);
});

test('tools run as the token owner and follow current backend permissions', function (): void {
    $owner = $this->owner();
    $issued = (new McpToken())->issueFor($owner, 'RBAC client', time() + 3600);

    $identity = $this->rpc($issued['token'], 'tools/call', ['name' => 'current-user']);
    $identity->assertSuccessful();
    $content = json_decode($identity->json('result.content.0.text'), true, flags: JSON_THROW_ON_ERROR);
    expect($content)->toMatchArray(['id' => $owner->id, 'username' => $owner->username]);
    expect(array_column($this->rpc($issued['token'], 'tools/list')->json('result.tools'), 'name'))
        ->toContain('current-user')->not->toContain('dictionary-list');
    $this->rpc($issued['token'], 'tools/call', ['name' => 'dictionary-list'])->assertJsonPath('error.code', -32602);

    $permissionId = $this->grant($owner, 'dictionary@index');
    expect(array_column($this->rpc($issued['token'], 'tools/list')->json('result.tools'), 'name'))->toContain('dictionary-list');
    $this->rpc($issued['token'], 'tools/call', ['name' => 'dictionary-list'])
        ->assertSuccessful()->assertJsonPath('result.isError', false);
    DB::table('role_has_permissions')->where('permission_id', $permissionId)->delete();
    $this->rpc($issued['token'], 'tools/call', ['name' => 'dictionary-list'])->assertJsonPath('error.code', -32602);
});

test('soft deleting the owner blocks its MCP credential until the owner is restored', function (): void {
    $owner = $this->owner();
    $issued = (new McpToken())->issueFor($owner, 'Deleted owner', time() + 3600);
    $owner->delete();
    $stored = McpToken::query()->findOrFail($issued['id']);
    expect($stored->status)->toBe(1)->and($stored->revoked_at)->toBe(0);
    $this->rpc($issued['token'])->assertUnauthorized();

    $owner->restore();
    $this->rpc($issued['token'])->assertSuccessful();
});

test('MCP middleware resolves its user independently of the backend authentication context', function (): void {
    $owner = $this->owner();
    $backend = $this->owner('backend-context');
    $issued = (new McpToken())->issueFor($owner, 'Independent client', time() + 3600);
    $login = $backend->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;
    $this->app->instance('request', Request::create('/', 'POST', [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $login]));
    $adminGuard = Auth::guard(getGuardName());
    $adminUser = $adminGuard->user();
    $adminFacade = Admin::getFacadeRoot();
    $adminContext = Admin::auth();
    $defaultGuard = Auth::getDefaultDriver();
    expect($adminUser->id)->toBe($backend->id)->and($adminContext->id)->toBe($backend->id);
    $request = Request::create('/ai/mcp', 'POST', [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer ' . $issued['token']]);
    $this->app->instance('request', $request);
    (new McpMiddleware())->handle($request, fn () => response()->json([]));
    $resolver = Auth::userResolver();

    expect($resolver()->id)->toBe($owner->id)
        ->and(Auth::guard(getGuardName()))->toBe($adminGuard)
        ->and($adminGuard->user())->toBe($adminUser)
        ->and(Admin::getFacadeRoot())->toBe($adminFacade)
        ->and(Admin::currentLoginUser())->toBe($adminContext)
        ->and(Auth::getDefaultDriver())->toBe($defaultGuard);
});

test('MCP accepts repeated requests with a valid credential', function (): void {
    $owner = $this->owner();
    $issued = (new McpToken())->issueFor($owner, 'HTTP client', time() + 3600);

    for ($attempt = 0; $attempt < 61; $attempt++) {
        $this->rpc($issued['token'])->assertSuccessful();
    }
});

test('MCP usage logs record tool outcomes with only the six agreed fields', function (): void {
    $owner = $this->owner();
    $issued = (new McpToken())->issueFor($owner, 'Logged client', time() + 3600);

    $this->rpc($issued['token']);
    $this->rpc($issued['token'], 'tools/list');
    $this->rpc('unknown', 'tools/call', ['name' => 'current-user'])->assertUnauthorized();
    expect(McpTokenLog::query()->count())->toBe(0);

    $this->rpc($issued['token'], 'tools/call', ['name' => 'current-user'])
        ->assertJsonPath('result.isError', false);
    $this->rpc($issued['token'], 'tools/call', ['name' => 'missing-tool'])
        ->assertJsonPath('error.code', -32602);
    $this->grant($owner, 'dictionary@index');
    $this->rpc($issued['token'], 'tools/call', ['name' => 'dictionary-list', 'arguments' => ['per_page' => 0]])
        ->assertJsonPath('result.isError', true);

    $logs = McpTokenLog::query()->orderBy('id')->get();
    expect(Schema::getColumnListing('system_mcp_token_logs'))
        ->toBe(['id', 'token_id', 'tool_name', 'ip', 'result', 'created_at'])
        ->and($logs->pluck('token_id')->all())->toBe(array_fill(0, 3, $issued['id']))
        ->and($logs->pluck('tool_name')->all())->toBe(['current-user', 'missing-tool', 'dictionary-list'])
        ->and($logs->pluck('result')->all())->toBe([1, 2, 2])
        ->and($logs->first()->ip)->toBe('127.0.0.1')
        ->and($logs->first()->created_at)->toBeGreaterThan(0);
});

test('MCP usage log listing authorizes access and resolves the bound account through its token', function (): void {
    $owner = $this->owner('bound-account');
    $operator = $this->owner('log-reader');
    $model = new McpToken();
    $issued = $model->issueFor($owner, 'Logged client', time() + 3600);
    $other = $model->issueFor($operator, 'Other client', time() + 3600);
    $this->rpc($issued['token'], 'tools/call', ['name' => 'current-user']);
    $replacement = $model->rotate($issued['id']);
    $this->rpc($replacement['token'], 'tools/call', ['name' => 'current-user']);
    $this->rpc($other['token'], 'tools/call', ['name' => 'current-user']);
    $model->revoke($issued['id']);

    Auth::forgetGuards();
    $login = $operator->createToken('Backend login', ['*'], now()->addHour())->plainTextToken;
    $headers = ['Authorization' => 'Bearer ' . $login, 'Request-from' => 'Dashboard'];
    $endpoint = '/' . config('catch.route.prefix') . '/system/mcp/tokens/' . $issued['id'] . '/usage-logs';
    $this->getJson($endpoint, $headers)->assertJsonPath('code', Code::PERMISSION_FORBIDDEN->value());
    $this->grant($operator, 'mcpTokens@usageLogs');

    $response = $this->getJson($endpoint . '?limit=1', $headers);
    $response->assertSuccessful()->assertJsonPath('code', Code::SUCCESS->value())
        ->assertJsonPath('total', 2)->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.token_id', $issued['id'])
        ->assertJsonPath('data.0.token.user.id', $owner->id)
        ->assertJsonPath('data.0.token.user.username', 'bound-account');
    expect(array_keys($response->json('data.0.token')))->toBe(['id', 'name', 'user_id', 'user'])
        ->and(array_keys($response->json('data.0.token.user')))->toBe(['id', 'username']);

    $this->getJson($endpoint . '?limit=1&page=2', $headers)
        ->assertJsonCount(1, 'data')->assertJsonPath('data.0.token_id', $issued['id']);
    $this->getJson($endpoint . '?token_id=' . $other['id'], $headers)
        ->assertUnprocessable()->assertJsonValidationErrors('token_id');
    $this->getJson($endpoint . '?limit=101&page=0', $headers)
        ->assertUnprocessable()->assertJsonValidationErrors(['limit', 'page']);

    $owner->delete();
    $this->getJson($endpoint, $headers)->assertJsonPath('data.0.token.user.username', 'bound-account');
    McpToken::query()->findOrFail($issued['id'])->delete();
    $this->getJson($endpoint, $headers)->assertJsonPath('data.0.token.user.username', 'bound-account');
});

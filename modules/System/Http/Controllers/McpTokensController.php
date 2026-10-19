<?php

declare(strict_types=1);

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Permissions\Exceptions\PermissionForbidden;
use Modules\System\Http\Requests\McpTokenLogRequest;
use Modules\System\Http\Requests\McpTokenRequest;
use Modules\System\Models\McpToken;
use Modules\System\Models\McpTokenLog;
use Modules\User\Models\User;

class McpTokensController extends CatchController
{
    public function __construct(protected readonly McpToken $model)
    {
    }

    public function index(): mixed
    {
        return $this->model->setBeforeGetList(fn ($query) => $query->with([
            'user' => fn ($query) => $query->without(['roles', 'jobs'])->select(['id', 'username']),
        ]))->getList();
    }

    public function store(McpTokenRequest $request): array
    {
        $data = $request->validated();
        $user = User::query()->findOrFail((int) $data['user_id']);

        return $this->model->issueFor($user, $data['name'], (int) $data['expires_at'])
            + ['endpoint' => route('mcp.admin')];
    }

    public function destroy(int $id): bool
    {
        return $this->model->revoke($id);
    }

    public function rotate(int $id): array
    {
        return $this->model->rotate($id)
            + ['endpoint' => route('mcp.admin')];
    }

    public function usageLogs(McpTokenLogRequest $request, int $id): LengthAwarePaginator
    {
        if (! $this->getLoginUser()->can('system@mcpTokens@usageLogs')) {
            throw new PermissionForbidden();
        }

        $data = $request->validated();

        return McpTokenLog::query()->with([
            'token' => fn ($query) => $query->select(['id', 'name', 'user_id'])->with([
                'user' => fn ($query) => $query->withTrashed()->without(['roles', 'jobs'])->select(['id', 'username']),
            ]),
        ])->where('token_id', $id)->orderByDesc('id')
            ->paginate((int) ($data['limit'] ?? 10), ['*'], 'page', (int) ($data['page'] ?? 1));
    }
}

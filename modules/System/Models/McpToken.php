<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel;
use Catch\Enums\Status;
use Catch\Exceptions\FailedException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;
use Random\RandomException;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $token_hash
 * @property int $status
 * @property int $expires_at
 * @property int $revoked_at
 * @property int $last_used_at
 * @property string $last_used_ip
 */
class McpToken extends CatchModel
{
    protected $table = 'system_mcp_tokens';

    protected $fillable = ['user_id', 'name', 'token_hash', 'status', 'expires_at', 'revoked_at', 'last_used_at', 'last_used_ip', 'created_at', 'updated_at', 'deleted_at'];

    protected $hidden = ['token_hash'];

    protected array $fields = ['id', 'user_id', 'name', 'status', 'expires_at', 'revoked_at', 'last_used_at', 'last_used_ip', 'created_at', 'updated_at'];

    protected array $form = ['user_id', 'name', 'expires_at'];

    public array $searchable = ['name' => 'like', 'user_id' => '=', 'status' => '='];

    protected $attributes = [
        'status' => 1,
        'revoked_at' => 0,
        'last_used_at' => 0,
        'last_used_ip' => '',
        'created_at' => 0,
        'updated_at' => 0,
        'deleted_at' => 0,
    ];

    protected array $defaultCasts = ['created_at' => 'integer', 'updated_at' => 'integer'];

    protected $casts = [
        'user_id' => 'integer',
        'status' => 'integer',
        'expires_at' => 'integer',
        'revoked_at' => 'integer',
        'last_used_at' => 'integer',
        'deleted_at' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return array{id: int, name: string, token: string, expires_at: int}
     */
    public function issueFor(User $user, string $name, int $expiresAt): array
    {
        $secret = bin2hex(random_bytes(32));
        $token = static::create([
            'user_id' => $user->id,
            'name' => $name,
            'token_hash' => hash('sha256', $secret),
            'expires_at' => $expiresAt,
        ]);

        return ['id' => $token->id, 'name' => $token->name, 'token' => $secret, 'expires_at' => $token->expires_at];
    }

    /**
     * @param int $id
     * @return array{id: int, name: string, token: string, expires_at: int}
     * @throws RandomException
     */
    public function rotate(int $id): array
    {
        $token = static::findOrFail($id);
        $secret = bin2hex(random_bytes(32));
        $updated = static::whereKey($id)
            ->where('token_hash', $token->token_hash)
            ->where('status', Status::Enable->value)
            ->where('revoked_at', 0)
            ->where('expires_at', '>', time())
            ->update(['token_hash' => hash('sha256', $secret), 'last_used_at' => 0, 'last_used_ip' => '']);

        if (! $updated) {
            throw new FailedException('令牌已失效或已被轮换，请刷新列表');
        }

        return ['id' => $token->id, 'name' => $token->name, 'token' => $secret, 'expires_at' => $token->expires_at];
    }

    public function revoke(int $id): bool
    {
        static::whereKey($id)->where('revoked_at', 0)
            ->update(['status' => Status::Disable->value, 'revoked_at' => time()]);

        return true;
    }
}

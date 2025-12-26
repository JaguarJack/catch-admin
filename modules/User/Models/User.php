<?php

namespace Modules\User\Models;

use Catch\Base\CatchModel as Model;
use Catch\Enums\Status;
use Catch\Facade\Admin;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Modules\User\Models\Traits\UserRelations;

/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $wx_pc_openid
 * @property string $unionid
 * @property string $avatar
 * @property string $password
 * @property int $creator_id
 * @property int $status
 * @property string $login_ip
 * @property int $login_at
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Permissions\Models\Roles> $roles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Permissions\Models\Jobs> $jobs
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Permissions\Models\Permissions>|null $permissions
 * /
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasApiTokens;
    use UserRelations;

    protected $fillable = [
        'id',
        'username',
        'email',
        'mobile',
        'wx_pc_openid',
        'unionid',
        'avatar',
        'password',
        'remember_token',
        'creator_id',
        'status',
        'department_id',
        'login_ip',
        'login_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array|string[]
     */
    public array $searchable = [
        'username' => 'like',
        'email' => 'like',
        'status' => '=',
    ];

    /**
     * @var string
     */
    protected $table = 'admin_users';

    protected array $fields = ['id', 'username', 'email', 'avatar', 'creator_id', 'status', 'department_id', 'created_at'];

    /**
     * @var array|string[]
     */
    protected array $form = ['username', 'email', 'password', 'department_id'];

    /**
     * @var array|string[]
     */
    protected array $formRelations = ['roles', 'jobs'];

    /**
     * password
     */
    protected function password(): Attribute
    {
        return new Attribute(
            set: fn ($value) => bcrypt($value),
        );
    }

    protected function departmentId(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ?: null,
            set: fn ($value) => $value ?: 0,
        );
    }

    /**
     * is super admin
     */
    public function isSuperAdmin(): bool
    {
        $configSuperAdminIds = config('catch.super_admin');

        if (is_array($configSuperAdminIds)) {
            return in_array($this->id, $configSuperAdminIds);
        }

        return $this->{$this->primaryKey} == $configSuperAdminIds;
    }

    /**
     * @return bool
     */
    public function isDisable(): bool
    {
        return Status::Disable->assert($this->status);
    }

    /**
     * update
     */
    public function updateBy($id, array $data): mixed
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // 更新用户清除缓存重新获取
        $this->find($id)->clearCache();

        return parent::updateBy($id, $data);
    }

    /**
     * @param  $id
     * @param  bool  $force
     * @param  bool  $softForce
     * @return bool|null
     */
    public function deleteBy($id, bool $force = false, bool $softForce = false): ?bool
    {
        return $this->transaction(function () use ($id) {
            /* @var  User $user */
            $user = $this->where('id', $id)->first();

            $user->clearCache();

            $user->tokens()->delete();

            return parent::deleteBy($id);
        });
    }

    /**
     * 清理缓存
     *
     * @return void
     */
    public function clearCache(): void
    {
        $this->tokens()->get()
            ->each(function ($token) {
                Admin::clearUserPersonalToken($token->id);
            });
    }
}

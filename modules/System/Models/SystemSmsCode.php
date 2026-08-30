<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\Trans;
use Catch\Traits\DB\WithAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $mobile
 * @property $code
 * @property $behavior
 * @property $channel
 * @property $status
 * @property $expired_at
 * @property $created_at
 * @property $updated_at
 */
class SystemSmsCode extends Model
{
    use BaseOperate;
    use ScopeTrait;
    use Trans;
    use WithAttributes;

    protected $dateFormat = 'U';

    protected $table = 'system_sms_code';

    protected $fillable = [
        'id',
        'mobile',
        'code',
        'behavior',
        'channel',
        'status',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    public const UN_USED = 1;

    public const USED = 2;

    public function hasCode(string $mobile, string $behavior): bool
    {
        $smsCode = $this->getCodeBy($mobile, $behavior);

        if (! $smsCode) {
            return false;
        }

        if ($smsCode->isExpired()) {
            return false;
        }

        if ($smsCode->isUsed()) {
            return false;
        }

        return true;
    }

    /**
     * @param  string  $mobile
     * @param  string  $behavior
     * @return ?SystemSmsCode
     */
    public function getCodeBy(string $mobile, string $behavior): ?SystemSmsCode
    {
        return self::where('mobile', $mobile)->where('behavior', $behavior)->first();
    }

    public function store(string $mobile, string $behavior, mixed $code): bool|int
    {
        $smsCode = $this->getCodeBy($mobile, $behavior);

        if (! $smsCode) {
            return $this->fill([
                'mobile' => $mobile,
                'behavior' => $behavior,
                'code' => $code,
                'channel' => config('sms.default'),
                'created_at' => time(),
                'updated_at' => time(),
                'status' => self::UN_USED,
            ])->save();
        } else {
            return self::query()->where('mobile', $mobile)
                ->where('behavior', $behavior)
                ->update([
                    'code' => $code,
                    'created_at' => time(),
                    'status' => self::UN_USED,
                    'updated_at' => time(),
                ]);
        }
    }

    public function validCode(string $mobile, string $behavior, mixed $code): bool
    {
        $smsCode = $this->getCodeBy($mobile, $behavior);

        if (! $smsCode) {
            return false;
        }

        if ($smsCode->isExpired()) {
            return false;
        }

        if ($smsCode->isUsed()) {
            return false;
        }

        if ((string) $smsCode->getAttribute('code') !== (string) $code) {
            return false;
        }

        $smsCode->status = self::USED;

        return $smsCode->save();
    }

    public function isExpired(): bool
    {
        $fiveMinute = 60 * 5;

        return ($this->created_at->timestamp + $fiveMinute) <= time();
    }

    public function isUsed(): bool
    {
        return $this->status == self::USED;
    }
}

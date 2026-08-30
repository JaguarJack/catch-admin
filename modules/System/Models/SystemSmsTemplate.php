<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\System\Enums\SmsChannel;

/**
 * @property $id
 * @property $driver
 * @property $template_id
 * @property $content
 * @property $variables
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class SystemSmsTemplate extends Model
{
    protected $table = 'system_sms_template';

    protected $fillable = ['id', 'channel', 'identify', 'template_id', 'content', 'variables', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'identify', 'channel', 'template_id', 'content', 'variables', 'created_at'];

    protected array $form = ['template_id', 'content', 'variables'];

    public array $searchable = [
        'channel' => '=',
    ];

    public function channel(): Attribute
    {
        return new Attribute(
            get: fn ($value) => SmsChannel::QCLOUD->assert($value) ? '腾讯云' : '阿里云'
        );
    }
}

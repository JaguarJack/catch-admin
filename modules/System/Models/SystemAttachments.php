<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

/**
 * @property $id
 * @property $filename
 * @property $path
 * @property $extension
 * @property $filesize
 * @property $mimetype
 * @property $driver
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class SystemAttachments extends Model
{
    protected $table = 'system_attachments';

    protected $fillable = ['id', 'category_id', 'filename', 'path', 'extension', 'filesize', 'mimetype', 'driver', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'category_id', 'filename', 'path', 'extension', 'filesize', 'mimetype', 'driver', 'created_at', 'updated_at'];

    public array $searchable = [
        'category_id' => '=',
        'filename' => 'like',
        'extension' => '=',
    ];

    public function path(): Attribute
    {
        return new Attribute(
            get: fn ($value) => url($value)
        );
    }

    public function filesize(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Number::fileSize((int) $value),
        );
    }

    public function driver(): Attribute
    {
        return new Attribute(
            get: fn ($value) => match ($value) {
                'local' => '本地',
                'cos' => '腾讯Cos',
                'qiniu' => '七牛云',
                default => '未知',
            }
        );
    }
}

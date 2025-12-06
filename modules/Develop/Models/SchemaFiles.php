<?php

namespace Modules\Develop\Models;

use Catch\Base\CatchModel as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class SchemaFiles extends Model
{
    protected $table = 'schema_files';

    protected $fillable = [
        'id', 'schema_id', 'controller_file', 'model_file', 'request_file', 'table_file', 'form_file', 'created_at', 'updated_at', 'deleted_at',
        'controller_path', 'model_path', 'request_path', 'table_path', 'form_path',
    ];

    protected bool $autoNull2EmptyString = false;

    protected function controllerPath(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->removeBasePath($value)
        );
    }

    protected function modelPath(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->removeBasePath($value)
        );
    }

    protected function requestPath(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->removeBasePath($value)
        );
    }

    protected function tablePath(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->removeWebPath($value)
        );
    }

    protected function formPath(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->removeWebPath($value)
        );
    }

    protected function removeBasePath($path)
    {
        return Str::of($path)->replace(base_path(), '')->replace('\\', '/');
    }

    protected function removeWebPath($path)
    {
        return Str::of($path)->replace(base_path('web'.DIRECTORY_SEPARATOR.'src'), '')->replace('\\', '/');
    }
}

<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel as Model;
use Catch\Enums\Status;

/**
 * @property $id
 * @property $dic_id
 * @property $label
 * @property $value
 * @property $sort
 * @property $status
 * @property $description
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class DictionaryValues extends Model
{
    protected $table = 'system_dictionary_values';

    protected $fillable = ['id', 'dic_id', 'label', 'key', 'value', 'sort', 'status', 'description', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'label', 'key','value', 'sort', 'status', 'description', 'created_at', 'updated_at'];

    protected array $form = ['dic_id', 'label', 'key', 'value', 'sort', 'description'];

    public array $searchable = [
        'dic_id' => '=',
        'label' => 'like',
        'status' => '=',
    ];

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getEnabledValues($id)
    {
        return static::where('dic_id', $id)->where('status', Status::Enable->value)->get(['label', 'value']);
    }
}

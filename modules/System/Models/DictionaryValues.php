<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel as Model;

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

    protected $fillable = [ 'id', 'dic_id', 'label', 'value', 'sort', 'status', 'description', 'creator_id', 'created_at', 'updated_at', 'deleted_at' ];

    /**
     * @var array
     */
    protected array $fields = ['id','label','value','sort','status','description','created_at','updated_at'];

    /**
     * @var array
     */
    protected array $form = ['dic_id', 'label','value','sort','description'];

    /**
     * @var array
     */
    public array $searchable = [
        'dic_ids' => '=',
        'label' => 'like',
        'status' => '=',
    ];
}

<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Catch\Base\CatchModel as Model;

/**
 * @property $id
 * @property $parent_id
 * @property $name
 * @property $slug
 * @property $order
 * @property $post_count
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
*/
class Category extends Model
{
    protected $table = 'cms_category';

    protected $fillable = [ 'id', 'parent_id', 'name', 'slug', 'order', 'post_count', 'creator_id', 'created_at', 'updated_at', 'deleted_at' ];

    /**
     * @var array
     */
    protected array $fields = ['id','parent_id','name','slug','order','post_count','created_at','updated_at'];

    /**
     * @var array
     */
    protected array $form = ['parent_id','name','slug','order'];

    /**
     * @var array
     */
    public array $searchable = [
        'name' => 'like'
    ];
}

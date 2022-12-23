<?php

declare(strict_types=1);

namespace Modules\Permissions\Models;

use Catch\Base\CatchModel as Model;

/**
 * @property $id
 * @property $parent_id
 * @property $department_name
 * @property $principal
 * @property $mobile
 * @property $email
 * @property $status
 * @property $sort
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
*/
class Departments extends Model
{
    protected $table = 'departments';

    protected $fillable = ['id', 'parent_id', 'department_name', 'principal', 'mobile', 'email', 'status', 'sort', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $isPaginate = false;

    /**
     * @var array
     */
    protected array $fields = ['id','parent_id','department_name','status','sort','created_at'];

    /**
     * @var array
     */
    protected array $form = ['parent_id','department_name','principal','mobile','email','sort'];

    /**
     * @var array
     */
    public array $searchable = [
        'department_name' => 'like',
        'status' => '=',
    ];

    protected bool $asTree = true;


    /**
     *
     * @param int|array $id
     * @return array
     */
    public function findFollowDepartments(int|array $id): array
    {
        if (!is_array($id)) {
           $id = [$id];
        }

        $followDepartmentIds = $this->whereIn($this->getParentIdColumn(), $id)->pluck('id')->toArray();

        if (! empty($followDepartmentIds)) {
            $followDepartmentIds = array_merge($followDepartmentIds, $this->findFollowDepartments($followDepartmentIds));
        }

        return $followDepartmentIds;
    }
}

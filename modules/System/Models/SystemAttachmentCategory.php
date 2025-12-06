<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Catch\Base\CatchModel as Model;

/**
 * @property $id
 * @property $title
 * @property $creator_id
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class SystemAttachmentCategory extends Model
{
    protected $table = 'system_attachment_category';

    protected $fillable = ['id', 'parent_id', 'title', 'creator_id', 'created_at', 'updated_at', 'deleted_at'];

    protected array $fields = ['id', 'parent_id', 'title'];

    protected array $form = ['title', 'parent_id', 'id'];

    protected bool $asTree = true;

    protected bool $isPaginate = false;
}

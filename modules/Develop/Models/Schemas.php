<?php

namespace Modules\Develop\Models;

use Catch\Base\CatchModel;
use Catch\Enums\Status;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Modules\Develop\Support\Generate\Create\Schema;

/**
 * @property int $id
 * @property string $module
 * @property string $name
 * @property string $columns
 * @property Status $is_soft_delete
 * @property string $generate_params
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Schemas extends CatchModel
{
    /**
     * @var string
     */
    protected $table = 'schemas';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'module', 'name', 'columns', 'is_soft_delete', 'generate_params',  'created_at', 'updated_at',
    ];

    protected array $fields = ['id', 'module', 'name', 'columns', 'is_soft_delete',  'created_at', 'updated_at'];

    /**
     * @var array|string[]
     */
    public array $searchable = ['module' => 'like', 'name' => 'like'];

    /**
     * @var string[]
     */
    protected $casts = [
        'is_soft_delete' => Status::class,
    ];

    /**
     * @throws Exception
     */
    public function storeBy(array $data): bool
    {
        // 从已有 schema 中选择
        if (isset($data['schema_name'])) {
            $columns = SchemaFacade::getColumnListing($data['schema_name']);

            return parent::storeBy([
                'module' => $data['module'],
                'name' => $data['schema_name'],
                'columns' => implode(',', $columns),
                'is_soft_delete' => isset($columns['deleted_at']) ? Status::Enable->value : Status::Disable->value,
            ]);
        }

        $schema = $data['schema'];
        $structures = $data['structures'];
        $schemaId = parent::storeBy([
            'module' => $schema['module'],
            'name' => $schema['name'],
            'columns' => implode(',', array_column($structures, 'field')),
            'is_soft_delete' => $schema['deleted_at'] ? Status::Enable : Status::Disable,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        try {
            $schemaCreate = new Schema($schema['name'], $schema['engine'], $schema['charset'], $schema['collection'], $schema['comment']);
            $schemaCreate->setStructures($structures)
                ->setModule($schema['module'])
                ->setCreatedAt($schema['created_at'])
                ->setCreatorId($schema['creator_id'])
                ->setUpdatedAt($schema['updated_at'])
                ->setDeletedAt($schema['deleted_at'])
                ->create();
        } catch (Exception $e) {
            parent::deleteBy($schemaId, true);
            throw $e;
        }

        return true;
    }

    public function show($id): Model
    {
        /* @var Schemas $schema */
        $schema = parent::firstBy($id);

        $columns = [];

        foreach (SchemaFacade::getColumns($schema->name) as $column) {
            $columns[] = [
                'name' => $column['name'],
                'type' => $column['type_name'],
                'nullable' => $column['nullable'],
                'default' => $column['default'],
                'comment' => $column['comment'],
            ];
        }

        $schema->columns = $columns;

        return $schema;
    }

    /**
     * 代码生成参数
     *
     * @return Attribute
     */
    public function generateParams(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ! $value ? null : json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}

<?php

namespace Modules\Develop\Models;

use Catch\Base\CatchModel;
use Catch\Enums\Status;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Develop\Support\Generate\Create\Schema;
use Illuminate\Support\Facades\Schema as SchemaFacade;

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
        'id', 'module', 'name', 'columns', 'is_soft_delete', 'created_at', 'updated_at'
    ];

    /**
     * @var array|string[]
     */
    public array $searchable = ['module' => 'like', 'name' => 'like'];

    /**
     * @var string[]
     */
    protected array $mergeCasts = [
        'is_soft_delete' => Status::class
    ];

    /**
     *
     * @param array $data
     * @return boolean
     * @throws Exception
     */
    public function storeBy(array $data): bool
    {
        $schema = $data['schema'];

        $structures = $data['structures'];

        $schemaId = parent::storeBy([
            'module' => $schema['module'],

            'name' => $schema['name'],

            'columns' => implode(',', array_column($structures, 'field')),

            'is_soft_delete' => $schema['deleted_at'] ? Status::Enable : Status::Disable
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


    /**
     * @param $id
     * @return Model
     */
    public function show($id): Model
    {
        $schema = parent::firstBy($id);

        $columns = [];

        foreach (getTableColumns($schema->name) as $columnString) {
            $column = DB::connection()->getDoctrineColumn(DB::connection()->getTablePrefix().$schema->name, $columnString);
            $columns[] = [
                'name' => $column->getName(),
                'type' => $column->getType()->getName(),
                'nullable' => ! $column->getNotnull(),
                'default' => $column->getDefault(),
                'comment' => $column->getComment()
            ];
        }

        $schema->columns = $columns;

        return $schema;
    }

    /**
     * delete
     *
     * @param $id
     * @param bool $force
     * @return bool|null
     */
    public function deleteBy($id, bool $force = false): ?bool
    {
        $schema = parent::firstBy($id);

        if ($schema->delete()) {
            SchemaFacade::dropIfExists($schema->name);
        }

        return true;
    }
}

<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema as MigrationSchema;

/**
 * schema
 */
class Schema extends Creator
{
    /**
     * @var bool
     */
    protected bool $createdAt = true;

    /**
     * @var bool
     */
    protected bool $updatedAt = true;

    /**
     * @var bool
     */
    protected bool $deletedAt = true;

    /**
     * @var bool
     */
    protected bool $creatorId = true;

    /**
     * @var array
     */
    protected array $structures = [];

    /**
     * @param string $table
     * @param string $engine
     * @param string $charset
     * @param string $collection
     * @param string $comment
     */
    public function __construct(
        public readonly string $table,
        public readonly string $engine,
        public readonly string $charset,
        public readonly string $collection,
        public readonly string $comment
    ) {
    }

    /**
     * create
     *
     * @return string|bool
     * @throws Exception
     */
    public function create(): string|bool
    {
        if (! count($this->structures)) {
            return false;
        }

        if (MigrationSchema::hasTable($this->table)) {
            throw new Exception(sprintf('[%s] 表已经存在', $this->table));
        }

        try {
            $this->createTable();

            if (MigrationSchema::hasTable($this->table)) {
                return parent::create();
            }

            return false;
        } catch (Exception $e) {
            MigrationSchema::dropIfExists($this->table);
            throw new Exception("由于{$e->getMessage()}, 表[{$this->table}]创建失败");
        }
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        return CatchAdmin::getModuleMigrationPath($this->module).date('Y_m_d_his_').'create_'.$this->table.'.php';
    }

    /**
     * create table
     *
     * @throws Exception
     */
    protected function createTable(): void
    {
        MigrationSchema::create($this->table, function (Blueprint $table) {
            foreach ($this->structures as $structure) {
                // if field && type hava value
                if ($structure['type'] && $structure['field']) {
                    if ($structure['type'] == 'string') {
                        $column = $table->string($structure['field'], $structure['length'] ?: 255);
                    } elseif ($structure['type'] == 'char') {
                        $column = $table->char($structure['field'], $structure['length']);
                    } else {
                        $column = $table->{$structure['type']}($structure['field']);
                    }

                    $column = $column->nullable($structure['nullable']);

                    if (is_null($structure['default'])) {
                    } else {
                        if (is_numeric($structure['default']) || mb_strlen($structure['default'])) {
                            $column = $column->default($structure['default']);
                        }
                    }

                    if ($structure['comment']) {
                        $column = $column->comment($structure['comment']);
                    }

                    if ($structure['unique']) {
                        $column->unique($structure['unique']);
                    }
                }
            }

            if ($this->creatorId) {
                $table->creatorId();
            }

            if ($this->createdAt) {
                $table->createdAt();
            }

            if ($this->updatedAt) {
                $table->updatedAt();
            }

            if ($this->deletedAt) {
                $table->deletedAt();
            }

            $table->charset = $this->charset;
            $table->engine = $this->engine;
            $table->collation = $this->collection;
            $table->comment($this->comment);
        });
    }

    /**
     * get migration content
     *
     * @return string
     */
    public function getContent(): string
    {
        $stub = File::get($this->getStub());

        return Str::of($stub)->replace(['{method}','{table}', '{content}'], ['create', $this->table, $this->getMigrationContent()])->toString();
    }

    /**
     * get content
     *
     * @return string
     */
    public function getMigrationContent(): string
    {
        $content = Str::of('');

        foreach ($this->structures as $structure) {
            $begin = Str::of('$table->');
            $type = Str::of($structure['type']);

            if ($type->exactly('string')) {
                $begin = $begin->append(sprintf("string('%s'%s)", $structure['field'], $structure['length'] ? ", {$structure['length']}" : ''));
            } elseif ($type->exactly('char')) {
                $begin = $begin->append(sprintf("char('%s', %s)", $structure['field'], $structure['length']));
            } elseif ($type->exactly('id')) {
                $begin = $begin->append(Str::of($structure['field'])->exactly('id') ? 'id()' : sprintf("id('%s')", $structure['field']));
            } else {
                $begin = $begin->append(sprintf("%s('%s')", $structure['type'], $structure['field']));
            }

            $content = $content->append($begin)
                            ->when($structure['nullable'], function ($str) {
                                return $str->append('->nullable()');
                            })
                            ->when(isset($structure['default']), function ($str) use ($structure){
                                $default = $structure['default'];

                                if (is_numeric($default)) {
                                    $default = intval($default);
                                    return $str->append("->default({$default})");
                                }

                                if ($default) {
                                    return $str->append("->default('{$default}')");
                                }

                                return $str;
                            })
                            ->when($structure['unique'], function ($str) {
                                return $str->append("->unique()");
                            })
                            ->when($structure['comment'], function ($str, $comment) {
                                return $str->append("->comment('{$comment}')");
                            })
                            ->append(';')
                            ->newLine();
        }

        if ($this->creatorId) {
            $content = $content->append(Str::of('$table->')->append('creatorId();'))->newLine();
        }

        if ($this->createdAt) {
            $content = $content->append(Str::of('$table->')->append('createdAt();'))->newLine();
        }

        if ($this->updatedAt) {
            $content = $content->append(Str::of('$table->')->append('updatedAt();'))->newLine();
        }

        if ($this->deletedAt) {
            $content = $content->append(Str::of('$table->')->append('deletedAt();'))->newLine();
        }

        return $content->newLine()
                       ->append("\$table->engine='{$this->engine}'")
                       ->append(';')
                       ->newLine()
                       ->append("\$table->comment('{$this->comment}')")
                       ->append(';')
                       ->toString();
    }

    /**
     * @param bool $createdAt
     * @return $this
     */
    public function setCreatedAt(bool $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param bool $updatedAt
     * @return $this
     */
    public function setUpdatedAt(bool $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @param bool $deletedAt
     * @return $this
     */
    public function setDeletedAt(bool $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @param bool $creatorId
     * @return $this
     */
    public function setCreatorId(bool $creatorId): static
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    /**
     * @param array $structures
     * @return $this
     */
    public function setStructures(array $structures): static
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * get stub
     *
     * @return string
     */
    protected function getStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'migration.stub';
    }
}

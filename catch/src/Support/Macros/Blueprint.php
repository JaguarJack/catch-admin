<?php

declare(strict_types=1);

namespace Catch\Support\Macros;

use Illuminate\Database\Schema\Blueprint as LaravelBlueprint;

class Blueprint
{
    /**
     * boot;
     */
    public static function boot(): void
    {
        $bluePrint = new static();

        $bluePrint->createdAt();

        $bluePrint->updatedAt();

        $bluePrint->deletedAt();

        $bluePrint->status();

        $bluePrint->creatorId();

        $bluePrint->unixTimestamp();

        $bluePrint->parentId();

        $bluePrint->sort();
    }

    /**
     * created unix timestamp
     *
     * @return void
     */
    public function createdAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('created_at')->default(0)->comment('created time');
        });
    }

    /**
     * update unix timestamp
     *
     * @return void
     */
    public function updatedAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('updated_at')->default(0)->comment('updated time');
        });
    }

    /**
     * soft delete
     *
     * @return void
     */
    public function deletedAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('deleted_at')->default(0)->comment('delete time');
        });
    }


    /**
     * unix timestamp
     *
     * @param bool $softDeleted
     * @return void
     */
    public function unixTimestamp(bool $softDeleted = true): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () use ($softDeleted) {
            $this->createdAt();
            $this->updatedAt();

            if ($softDeleted) {
                $this->deletedAt();
            }
        });
    }

    /**
     * creator id
     *
     * @return void
     */
    public function creatorId(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('creator_id')->default(0)->comment('creator id');
        });
    }


    /**
     * parent ID
     *
     * @return void
     */
    public function parentId(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('parent_id')->default(0)->comment('parent id');
        });
    }


    /**
     * status
     *
     * @return void
     */
    public function status(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function ($default = 1) {
            $this->tinyInteger('status')->default($default)->comment('1:normal 2: forbidden');
        });
    }

    /**
     * sort
     *
     * @param int $default
     * @return void
     */
    public function sort(int $default = 1): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () use ($default) {
            $this->integer('sort')->comment('sort')->default($default);
        });
    }
}

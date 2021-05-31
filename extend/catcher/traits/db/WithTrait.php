<?php
declare(strict_types=1);

/**
 * @filename  ScopeTrait.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\traits\db;

trait WithTrait
{
    /**
     *
     * @time 2021年05月28日
     * @return mixed
     */
    protected function autoWithRelation()
    {
        if (property_exists($this, 'globalScope')) {
            array_push($this->globalScope, 'withRelation');

        }
        $this->scope('scopeWith');
        if (property_exists($this, 'with')) {


            return $this->with($this->with);

        }

        return $this;
    }

    /**
     *
     * @time 2021年05月28日
     * @param $query
     * @return void
     */
    public function scopeWithRelation($query)
    {
        if (property_exists($this, 'with') && !empty($this->with)) {
            $query->with($this->with);
        }
    }

    /**
     *
     * @time 2021年05月28日
     * @param string $withRelation
     * @return $this
     */
    public function withoutRelation(string $withRelation)
    {
        $withes = $this->getOptions('with');

        foreach ($withes as $k => $item) {
            if ($item === $withRelation) {
                unset($withes[$k]);
                break;
            }
        }

       return $this->setOption('with', $withes);

    }

    /**
     *
     * @time 2021年05月28日
     * @param string $withRelation
     * @return $this
     */
    public function withOnlyRelation(string $withRelation)
    {
        return $this->with($withRelation);
    }

}
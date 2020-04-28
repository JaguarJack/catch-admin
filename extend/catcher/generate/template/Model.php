<?php
namespace JaguarJack\Generator\Template;

trait Model
{
    use Content;

    public function getList()
    {
        return <<<TMP
    public function getList()
    {
        return \$this->catchSearch()
                    ->order(\$this->getPk(), 'desc')
                    ->paginate();
    }
TMP;
    }

    /**
     * 一对一关联
     *
     * @time 2020年04月24日
     * @param $model
     * @param string $foreignKey
     * @param string $pk
     * @return string
     */
    public function hasOne($model, $foreignKey = '', $pk = '')
    {
        $func = lcfirst($model);

        return <<<TMP
    public function {$func}()
    {
       return \$this->hasOne({$model}::class{$this->keyRelate($foreignKey, $pk)}); 
    }
TMP;

    }

    /**
     *
     *
     * @time 2020年04月24日
     * @param $model
     * @param string $foreignKey
     * @param string $pk
     * @return string
     */
    public function hasMany($model, $foreignKey = '', $pk = '')
    {
        $func = lcfirst($model);

        return <<<TMP
    public function {$func}()
    {
       return \$this->hasMany({$model}::class{$this->keyRelate($foreignKey, $pk)}); 
    }
TMP;
    }

    /**
     * 远程一对多
     *
     * @time 2020年04月24日
     * @param $model
     * @param $middleModel
     * @param string $foreignKey
     * @param string $pk
     * @param string $middleRelateId
     * @param string $middleId
     * @return string
     */
    public function hasManyThrough($model, $middleModel, $foreignKey = '', $pk = '', $middleRelateId = '', $middleId = '')
    {
        $func = lcfirst($model);

        return <<<TMP
    public function {$func}()
    {
       return \$this->hasManyThrough({$model}::class, {$middleModel}::class{$this->keyRelate($foreignKey, $pk, $middleRelateId, $middleId)}); 
    }
TMP;
    }

    /**
     * 远程一对一
     *
     * @time 2020年04月24日
     * @param $model
     * @param $middleModel
     * @param string $foreignKey
     * @param string $pk
     * @param string $middleRelateId
     * @param string $middleId
     * @return string
     */
    public function hasOneThrough($model, $middleModel, $foreignKey = '', $pk = '', $middleRelateId = '', $middleId = '')
    {
        $func = lcfirst($model);

        return <<<TMP
    public function {$func}()
    {
       return \$this->hasOneThrough({$model}::class, {$middleModel}::class{$this->keyRelate($foreignKey, $pk, $middleRelateId, $middleId)}); 
    }
TMP;
    }

    /**
     * 多对多关联
     *
     * @time 2020年04月24日
     * @param $model
     * @param string $table
     * @param string $foreignKey
     * @param string $relateKey
     * @return string
     */
    public function belongsToMany($model, $table = '', $foreignKey = '', $relateKey = '')
    {
        $func = lcfirst($model);

        $table = !$table ? : ','.$table;

        $relateKey = !$relateKey ? : ','.$relateKey;

        return <<<TMP
    public function {$func}()
    {
       return \$this->hasOneThrough({$model}::class{$table}{$this->keyRelate($foreignKey)}{$relateKey}); 
    }
TMP;
    }

    /**
     * 模型关联key
     *
     * @time 2020年04月24日
     * @param string $foreignKey
     * @param string $pk
     * @param string $middleRelateId
     * @param string $middleId
     * @return string
     */
    public function keyRelate($foreignKey = '', $pk = '', $middleRelateId = '', $middleId = '')
    {
        return !$foreignKey ? : ',' . $foreignKey .
               !$middleRelateId ? : ','. $middleRelateId .
               !$pk ? : ',' . $pk .
               !$middleId ? : ',' . $middleId;

    }
}
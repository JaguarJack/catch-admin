<?php
namespace catcher\generate\template;

class Model
{
    use Content;

    public function createModel($model, $table)
    {
        return <<<TMP
class {$model} extends Model
{
    {CONTENT} 
}
TMP;
    }

    public function useTrait($hasDeletedAt = true)
    {
        if (!$hasDeletedAt) {
            return <<<TMP
use BaseOptionsTrait,ScopeTrait; 


TMP;
        }
    }

    public function uses($hasDeletedAt = true)
    {
        if ($hasDeletedAt) {
            return <<<TMP
use catcher\base\CatchModel as Model;


TMP;
        } else {
            return <<<TMP
use think\Model; 
use catcher\\traits\db\BaseOptionsTrait;
use catcher\\traits\db\ScopeTrait;


TMP;

        }

    }

    /**
     * name
     *
     * @time 2020年04月28日
     * @param $name
     * @return string
     */
    public function name($name)
    {
        if ($name) {
            return <<<TMP
protected \$name = '{$name}';


TMP;
        }
    }

    /**
     * field
     *
     * @time 2020年04月28日
     * @param $field
     * @return string
     */
    public function field($field)
    {
        if ($field) {
            return <<<TMP
    protected \$field = [
        {$field}
    ];
    
 
TMP;
        }

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
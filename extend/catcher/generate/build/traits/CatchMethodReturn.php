<?php
namespace catcher\generate\build\traits;


use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Return_;

trait CatchMethodReturn
{
    /**
     * 列表
     *
     * @time 2020年11月18日
     * @param $model
     * @return $this
     */
    public function index($model)
    {
        $class = new Name('CatchResponse');

        $arg = new Arg(new MethodCall(
            new PropertyFetch(
                new Variable('this'), new Identifier($model)
            ),
            new Identifier('getList')
        ));

        $this->methodBuild->addStmt(new Return_(new StaticCall($class, 'paginate', [$arg])));

        return $this;
    }

    /**
     * 保存
     *
     * @time 2020年11月18日
     * @param $model
     * @return $this
     */
    public function save($model)
    {
        $arg = new Arg(new MethodCall(
            new PropertyFetch(
                new Variable('this'), new Identifier($model)
            ),
            new Identifier('storeBy'), [new Arg(new MethodCall(new Variable('request'), new Identifier('post')))]
        ));

        $class = new Name('CatchResponse');

        $this->methodBuild->addStmt(new Return_(new StaticCall($class, 'success', [$arg])));

        return $this;
    }

    /**
     * 更新
     *
     * @time 2020年11月18日
     * @param $model
     * @return $this
     */
    public function update($model)
    {
        $arg = new Arg(new MethodCall(
            new PropertyFetch(
                new Variable('this'), new Identifier($model)
            ),
            new Identifier('updateBy'), [
                new Arg(new Variable('id')),
                new Arg(new MethodCall(new Variable('request'), new Identifier('post')))
            ]
        ));

        $class = new Name('CatchResponse');

        $this->methodBuild->addStmt(new Return_(new StaticCall($class, 'success', [$arg])));

        return $this;
    }

    public function read($model)
    {
        $arg = new Arg(new MethodCall(
            new PropertyFetch(
                new Variable('this'), new Identifier($model)
            ),
            new Identifier('findBy'), [
                new Arg(new Variable('id'))
            ]
        ));

        $class = new Name('CatchResponse');

        $this->methodBuild->addStmt(new Return_(new StaticCall($class, 'success', [$arg])));

        return $this;
    }

    /**
     * 删除
     *
     * @time 2020年11月18日
     * @param $model
     * @return $this
     */
    public function delete($model)
    {
        $arg = new Arg(new MethodCall(
            new PropertyFetch(
                new Variable('this'), new Identifier($model)
            ),
            new Identifier('deleteBy'), [
                new Arg(new Variable('id'))
            ]
        ));

        $class = new Name('CatchResponse');

        $this->methodBuild->addStmt(new Return_(new StaticCall($class, 'success', [$arg])));

        return $this;
    }
}
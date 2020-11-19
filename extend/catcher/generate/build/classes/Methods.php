<?php
namespace catcher\generate\classes;

class Methods extends Iteration
{
    protected $method = [];

    /**
     * set method name
     *
     * @time 2020年11月16日
     * @param string $method
     * @return $this
     */
    public function name(string $method)
    {
        $this->method['name'] = $method;

        return $this;
    }

    /**
     * set visible
     *
     * @time 2020年11月16日
     * @param string $visibility
     * @return $this
     */
    public function visible(string $visibility)
    {
        $this->method['visible'] = $visibility;

        return $this;
    }

    public function makePublic()
    {
        $this->method['visible'] = 'public';

        return $this;
    }

    public function makeProtected()
    {
        $this->method['visible'] = 'protected';

        return $this;
    }

    public function makePrivate()
    {
        $this->method['visible'] = 'private';

        return $this;
    }

    /**
     * set params
     *
     * @time 2020年11月16日
     * @param $type
     * @param $param
     * @param $default
     * @return $this
     */
    public function param($param, $type = null, $default = null)
    {
        $this->method['params'][] = [
            'type' => $type,
            'param' => $param,
            'default' => $default,
        ];

        return $this;
    }

    /**
     * 返回内容
     *
     * @time 2020年11月17日
     * @param $return
     * @return $this
     */
    public function return($return)
    {
        $this->method['return'] = $return;
        return $this;
    }

    /**
     * 返回值
     *
     * @time 2020年11月16日
     * @param $return
     * @return $this
     */
    public function returnType($return)
    {
        $this->method['return'] = $return;

        return $this;
    }


    /**
     * 注释
     *
     * @time 2020年11月16日
     * @param $comment
     * @return $this
     */
    public function docComment($comment)
    {
        $this->method['comment'] = $comment;

        return $this;
    }

    /**
     * 抽象
     *
     * @time 2020年11月17日
     * @return $this
     */
    public function toAbstract()
    {
        $this->method['type'] = 'Abstract';

        return $this;
    }

    /**
     * final
     *
     * @time 2020年11月17日
     * @return $this
     */
    public function toFinal()
    {
        $this->method['type'] = 'Final';

        return $this;
    }

    /**
     * join
     *
     * @time 2020年11月17日
     * @return $this
     */
    public function join()
    {
        $this->data[] = $this->method;

        $this->method = [];

        return $this;
    }
}

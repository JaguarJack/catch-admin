<?php
namespace catchAdmin\permissions\controller;

use catcher\base\CatchController;
use catchAdmin\permissions\model\Department as DepartmentModel;

class Department extends CatchController
{
    protected $department;

    public function __construct(DepartmentModel $department)
    {
        $this->department = $department;
    }

    public function index()
    {

    }

    public function save()
    {

    }

    public function update()
    {

    }

    public function delete($id)
    {

    }
}

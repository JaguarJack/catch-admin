<?php
namespace catchAdmin\cms;

use catcher\ModuleService;

class CmsService extends ModuleService
{
    public function loadRouteFrom()
    {
        // TODO: Implement loadRouteFrom() method.
        return __DIR__ . DIRECTORY_SEPARATOR . 'route.php';
    }
}

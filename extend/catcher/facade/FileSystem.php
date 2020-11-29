<?php
declare(strict_types=1);

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\facade;

use think\Facade;

/**
 * @method static  exists($path)
 * @method static  sharedGet($path)
 * @method static  requireOnce($file)
 * @method static  hash($path)
 * @method static  put($path, $contents, $lock = false)
 * @method static  replace($path, $content)
 * @method static  prepend($path, $data)
 * @method static  append($path, $data)
 * @method static  chmod($path, $mode = null)
 * @method static  delete($paths)
 * @method static  move($path, $target)
 * @method static  copy($path, $target)
 * @method static  link($target, $link)
 * @method static  name($path)
 * @method static  basename($path)
 * @method static  dirname($path)
 * @method static  extension($path)
 * @method static  type($path)
 * @method static  mimeType($path)
 * @method static  size($path)
 * @method static  lastModified($path)
 * @method static  isDirectory($directory)
 * @method static  isReadable($path)
 * @method static  isWritable($path)
 * @method static  isFile($file)
 * @method static  glob($pattern, $flags = 0)
 * @method static  files($directory, $hidden = false)
 * @method static  allFiles($directory, $hidden = false)
 * @method static  directories($directory)
 * @method static  makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
 * @method static  moveDirectory($from, $to, $overwrite = false)
 * @method static  copyDirectory($directory, $destination, $options = null)
 * @method static  deleteDirectory($directory, $preserve = false)
 * @method static   deleteDirectories($directory)
 * @method static  cleanDirectory($directory)
*/
class FileSystem extends Facade
{
    protected static function getFacadeClass()
    {
        return \catcher\library\FileSystem::class;
    }
}
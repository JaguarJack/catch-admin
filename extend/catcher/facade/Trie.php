<?php
declare(strict_types=1);

namespace catcher\facade;

use think\Facade;

/**
 * @method static \catcher\library\Trie add(string $word)
 * @method static \catcher\library\Trie filter(string $content)
 *
 * @time 2020年05月22日
 */
class Trie extends Facade
{
    protected static function getFacadeClass()
    {
        return \catcher\library\Trie::class;
    }
}

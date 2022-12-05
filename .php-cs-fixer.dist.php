<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'fixer'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    // 排除目录
    //->exclude('packages')
    //// ->notPath('./packages/test.php')
    // in 配置需要规则的目录
    ->in([
        __DIR__.DIRECTORY_SEPARATOR.'app',

        __DIR__.DIRECTORY_SEPARATOR.'catch',

        __DIR__.DIRECTORY_SEPARATOR.'modules',
    ])
    // 排除 . 开头的文件
    ->ignoreDotFiles(true)
    // vcs 文件
    ->ignoreVCS(true);

$config = new Config();

return $config->setRules([
    '@PSR1' => true, // psr1

    '@PSR2' => true, //  psr2 规范

    '@PSR12' => true, // psr12 规范

    'binary_operator_spaces' => true, // 二元操作符号空格 $a=1 => $a = 1;

    'array_syntax' => [
        'syntax' => 'short', // array('1') => ['1']
    ],

    'no_trailing_comma_in_singleline_array' => true, // -$a = array('sample',  ); => $a = array('sample');

    'trim_array_spaces' => true, // array( 'a', 'b' ); => array('a', 'b')

    'single_trait_insert_per_statement' => false,

    'standardize_not_equals' => true, // "!=" => "<>"

    'magic_constant_casing' => true, // __dir__ => __DIR__

    'native_function_casing' => true, // STRLEN($str); => strlen($str);

    'cast_spaces' => true, // (int)$b => (int) $b

    'simplified_if_return' => true, // if ($foo) { return true; } return false; => return (bool) ($foo)      ;

    'no_unused_imports' => true, //  use \DateTime; -use \Exception; => use \DateTime;

    'not_operator_with_successor_space' => true, // if (!$bar)  => if (! $bar)

    /**
     * // function example($b) {
    if ($b) {
    return;
    }
    - return;
     */
    'no_useless_return' => true,

    /**
     * function a() {
    -    $a = 1;
    -    return $a;
    +    return 1;
     */
    'return_assignment' => true,

    /**
    -<?php return null;
    +<?php return;
     */
    'simplified_null_return' => true,

    /**
     * $foo = [
    -   'bar' => [
    -    'baz' => true,
    -  ],
    +    'bar' => [
    +        'baz' => true,
    +    ],
     */
    'array_indentation' => true,

    /**
     * -$sample = $b [ 'a' ] [ 'b' ];
    +$sample = $b['a']['b'];
     */
    'no_spaces_around_offset' => true,

    'concat_space' => true,  // $a.$b => $a . $b
])->setFinder($finder);

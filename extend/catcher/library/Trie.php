<?php
namespace catcher\library;

use catcher\CatchCacheKeys;
use think\facade\Cache;

class Trie
{
    protected  $tree = [];

    protected $end = 'end';

    protected $sensitiveWord = '';

    protected $sensitiveWords = [];

    /**
     * add
     *
     * @time 2020年06月17日
     * @param string $word
     * @return $this
     */
    public function add(string $word)
    {
        $words = mb_str_split($word);

        $array = [];

        $len = count($words);

        $end = true;
        while ($len > 0) {
             if ($end) {
                 $array[] = [
                     $words[$len - 1] => ['end' => true],
                 ];
             } else {
                $latest = array_pop($array);
                $array[] = [
                    $words[$len-1] => $latest,
                ];
             }
             $end = false;
             $len--;
        }

        $this->tree = array_merge_recursive($this->tree, array_pop($array));

        return $this;
    }

    /**
     * 获取
     *
     * @time 2020年06月17日
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @return array|bool
     */
    public function getTries()
    {
        if (!empty($this->tree)) {
            return $this->tree;
        }

        return Cache::store('redis')->get(CatchCacheKeys::TRIE_TREE);
    }

    /**
     * 获取敏感词
     *
     * @time 2020年06月17日
     * @param array $trieTree
     * @param string $content
     * @param bool $all
     * @return array|string
     */
    public function getSensitiveWords(array $trieTree, string $content, $all = true)
    {
        $words = mb_str_split($content);
        $len = count($words);
        for ($start = 0; $start < $len; $start++) {
            // 未搜索到
            if (!isset($trieTree[$words[$start]]))  {
                continue;
            }
            $node = $trieTree[$words[$start]];
            $this->sensitiveWord = $words[$start];
            for ($i = $start+1; $i< $len; $i++) {
                $node = $node[$words[$i]] ?? null;
                $this->sensitiveWord .= $words[$i];
                if (isset($node['end'])) {
                    if ($all) {
                        $this->sensitiveWords[] = $this->sensitiveWord;
                        $this->sensitiveWord = '';
                    } else {
                       break 2;
                    }
                }
                if (!$node) {
                    $this->sensitiveWord = '';
                    $start = $i-1;
                    break;
                }
            }
        }

        return $all ? $this->sensitiveWords : $this->sensitiveWord;
    }

    /**
     * replace
     *
     * @time 2020年06月17日
     * @param $tree
     * @param string $content
     * @return string|string[]
     */
    public function replace($tree, string $content)
    {
        $sensitiveWords = $this->getSensitiveWords($tree, $content);

        $replace = [];

        foreach ($sensitiveWords as $word) {
            $replace[] = str_repeat('*', mb_strlen($word));
        }

        return str_replace($sensitiveWords, $replace, $content);
    }

    /**
     * cache
     *
     * @time 2020年06月17日
     */
    public function cached()
    {
        Cache::store('redis')->set(CatchCacheKeys::TRIE_TREE, $this->tree);
    }
}
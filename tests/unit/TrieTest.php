<?php
namespace catchAdmin\tests\unit;

use PHPUnit\Framework\TestCase;
use catcher\library\Trie;

class TrieTest extends TestCase
{

    protected function getTries()
    {
        $words = [
            '你大爷', '尼玛', 'SB'
        ];

        $trie = new Trie();

        foreach ($words as $word) {
            $trie->add($word);
        }

        return $trie->getTries();
    }
    public function testData()
    {
        $this->assertEquals([
            '你' => ['大' => ['爷' => ['end' => true]]],
            '尼' => ['玛' => ['end' => true]],
            'S' => ['B'  => ['end' => true]],
        ], $this->getTries());
    }

    public function testReplace()
    {
        $string = '你大爷的真尼玛SB';

        $this->assertEquals('***的真****',(new Trie())->replace($this->getTries(), $string));
    }

    public function testHasSensitiveWord()
    {
        $string = '你大爷的真尼玛SB';

        $res = (new Trie())->getSensitiveWords($this->getTries(), $string, false);


        $this->assertEquals('你大爷', $res);
    }
}
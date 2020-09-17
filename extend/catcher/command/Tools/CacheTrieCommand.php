<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catchAdmin\system\model\SensitiveWord;
use catcher\library\Trie;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class CacheTrieCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('cache:sensitiveWord')
            ->setDescription('cache sensitive word');
    }

    protected function execute(Input $input, Output $output)
    {
        $words = SensitiveWord::cursor();

        $trie = new Trie();

        foreach ($words as $word) {
            $trie->add($word->word);
        }

        if ($trie->cached()) {
            $output->info('trie cached');
        } else {
            $output->error('trie cached failed');
        }
    }
}

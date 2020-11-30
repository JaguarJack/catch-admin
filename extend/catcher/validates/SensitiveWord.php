<?php
declare(strict_types=1);

namespace catcher\validates;

use catcher\library\Trie;

class SensitiveWord implements ValidateInterface
{
    protected $word;

    public function type(): string
    {
        // TODO: Implement type() method.
        return 'sensitive_word';
    }

    public function verify($value): bool
    {
        $trie = app(Trie::class);

        $word = $trie->getSensitiveWords($trie->getTries(), $value, false);

        return !$word;
    }

    public function message(): string
    {
        // TODO: Implement message() method.
        return '内容包含敏感词';
    }
}

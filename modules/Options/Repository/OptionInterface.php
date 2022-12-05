<?php

namespace Modules\Options\Repository;

interface OptionInterface
{
    /**
     * @return array{label: string, value: string|number }
     */
    public function get(): array;
}

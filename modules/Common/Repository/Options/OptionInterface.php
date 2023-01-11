<?php

namespace Modules\Common\Repository\Options;

interface OptionInterface
{
    /**
     * @return array{label: string, value: string|number }
     */
    public function get(): array;
}

<?php

namespace Modules\Common\Repository\Options;

use Illuminate\Support\Collection;

interface OptionInterface
{
    public function get(): array|Collection;
}

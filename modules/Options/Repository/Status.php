<?php

namespace Modules\Options\Repository;

use Catch\Enums\Status as StatusEnum;

class Status implements OptionInterface
{
    public function get(): array
    {
        return [
            [
                'label' => StatusEnum::Enable->name(),
                'value' => StatusEnum::Enable->value()
            ],

            [
                'label' => StatusEnum::Disable->name(),
                'value' => StatusEnum::Disable->value()
            ]
        ];
    }
}

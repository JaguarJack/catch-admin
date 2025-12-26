<?php

namespace Modules\Common\Repository\Options;

use Catch\Enums\Status;
use Illuminate\Support\Collection;
use Modules\System\Models\Dictionary;

class Dictionaries implements OptionInterface
{
    public function get(): array|Collection
    {
        $dictionary = [];
        // TODO: Implement get() method.
        Dictionary::where('status', Status::Enable->value())
            ->get()
            ->each(function (Dictionary $item) use (&$dictionary) {
                $dictionary[] = [
                    'label' => $item->name,
                    'value' => $item->id,
                ];
            });

        return $dictionary;
    }
}

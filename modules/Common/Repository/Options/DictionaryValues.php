<?php

namespace Modules\Common\Repository\Options;

use Catch\Enums\Status;
use Illuminate\Support\Collection;
use Modules\System\Models\DictionaryValues as DictionaryValuesModel;

class DictionaryValues implements OptionInterface
{
    public function get(): array|Collection
    {
        $dictionary = [];
        // TODO: Implement get() method.
        DictionaryValuesModel::where('status', Status::Enable->value())
            ->where('dic_id', request()->get('dic_id'))
            ->get()
            ->each(function (DictionaryValuesModel $item) use (&$dictionary) {
                $dictionary[] = [
                    'label' => $item->label,
                    'value' => $item->value,
                ];
            });

        return $dictionary;
    }
}

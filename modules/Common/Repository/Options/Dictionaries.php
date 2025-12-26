<?php
namespace Modules\Common\Repository\Options;

use Illuminate\Support\Collection;
use Modules\System\Models\Dictionary;
use Catch\Enums\Status;

class Dictionaries implements OptionInterface
{

    public function get(): array|Collection
    {
        $dictionary = [];
        // TODO: Implement get() method.
        Dictionary::where('status',Status::Enable->value())
            ->get()
            ->each(function (Dictionary $item) use (&$dictionary){
               $dictionary[] = [
                   'label' => $item->name,
                   'value' => $item->id
               ];
            });

        return $dictionary;
    }
}

<?php

declare(strict_types=1);

namespace Modules\System\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Modules\System\Models\Dictionary;
use Modules\User\Models\User;

#[IsReadOnly]
class DictionaryList extends Tool
{
    protected string $name = 'dictionary-list';

    protected string $description = 'List system dictionaries permitted by the current backend user permissions.';

    public function shouldRegister(Request $request): bool
    {
        $user = $request->user();

        return $user instanceof User && $user->can('system@dictionary@index');
    }

    public function handle(Request $request): Response
    {
        if (! $this->shouldRegister($request)) {
            return Response::error('Permission denied.');
        }

        $arguments = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $model = new Dictionary();
        $query = $model->newQuery();

        if (method_exists($model, 'scopeDataRange')) {
            $query->dataRange();
        }

        $dictionaries = $query
            ->when(isset($arguments['name']), fn ($query) => $query->where('name', 'like', '%' . $arguments['name'] . '%'))
            ->orderByDesc('id')
            ->paginate($arguments['per_page'] ?? 20, ['id', 'name', 'key', 'description', 'status'], 'page', $arguments['page'] ?? 1);

        return Response::json([
            'data' => $dictionaries->items(),
            'total' => $dictionaries->total(),
            'page' => $dictionaries->currentPage(),
            'per_page' => $dictionaries->perPage(),
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Filter by dictionary name.'),
            'page' => $schema->integer()->min(1)->default(1),
            'per_page' => $schema->integer()->min(1)->max(100)->default(20),
        ];
    }
}

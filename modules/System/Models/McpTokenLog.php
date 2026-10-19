<?php

declare(strict_types=1);

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class McpTokenLog extends Model
{
    protected $table = 'system_mcp_token_logs';

    public $timestamps = false;

    protected $fillable = ['token_id', 'tool_name', 'ip', 'result', 'created_at'];

    protected $casts = ['token_id' => 'integer', 'result' => 'integer', 'created_at' => 'integer'];

    public function token(): BelongsTo
    {
        return $this->belongsTo(McpToken::class, 'token_id')->withTrashed();
    }
}

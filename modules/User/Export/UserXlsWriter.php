<?php

namespace Modules\User\Export;

use Catch\Contracts\AsyncTaskInterface;
use Illuminate\Support\LazyCollection;
use Modules\System\Support\Traits\AsyncTaskDispatch;
use Catch\Support\Excel\XlsWriterExport;

class UserXlsWriter extends XlsWriterExport implements AsyncTaskInterface
{
    use AsyncTaskDispatch;

    protected array $header = [
        'id', '昵称', '邮箱', '创建时间',
    ];

    public function array(): array|LazyCollection
    {
        // TODO: Implement array() method.
        return \Modules\User\Models\User::query()
            ->select('id', 'username', 'email', 'created_at')
            ->without('roles')
            ->limit(10000)
            ->cursor()
            ->chunk(100);
    }


    public function formats(): array
    {
        $handle = $this->excelObject->getHandle();

        $format    = new \Vtiful\Kernel\Format($handle);
        $boldStyle = $format->bold()->fontSize(10)->toResource();

        return [
            ['A1', 10, $boldStyle]
        ];
    }

    /**
     * 编辑密码保护，注意不是文档密码保护
     *
     * @return string|null
     */
    public function password():?string
    {
        return '123456';
    }
}

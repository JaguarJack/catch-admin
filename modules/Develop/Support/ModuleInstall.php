<?php
namespace Modules\Develop\Support;


use Catch\CatchAdmin;
use Catch\Exceptions\FailedException;
use Catch\Facade\Zipper;
use Illuminate\Support\Facades\File;

/**
 * module install
 */
class ModuleInstall
{
    const NORMAL_INSTALL = 1;
    const ZIP_INSTALL = 2;

    public function __construct(protected readonly int|string $type){}

    /**
     *
     * @param array $params
     */
    public function install(array $params): void
    {
        try {
            if ($this->type === self::NORMAL_INSTALL) {
                $this->installWithTitle($params['title']);
            }

            if ($this->type == self::ZIP_INSTALL) {
                $this->installWithZip($params['title'], $params['file']);
            }
        } catch (\Exception $e) {
            if ($this->type == self::ZIP_INSTALL) {
                CatchAdmin::deleteModulePath($params['title']);
            }

            throw new FailedException('安装失败: ' . $e->getMessage());
        }
    }

    /**
     *
     * @param string $title
     */
    protected function installWithTitle(string $title): void
    {
        try {
            $installer = CatchAdmin::getModuleInstaller($title);

            $installer->install();
        } catch (\Exception|\Throwable $e) {
            // CatchAdmin::deleteModulePath($title);

            throw new FailedException('安装失败: ' . $e->getMessage());
        }
    }

    /**
     * get
     *
     * @param string $title
     * @param string $zip
     */
    protected function installWithZip(string $title, string $zip): void
    {
        $zipRepository = Zipper::make($zip)->getRepository();

        $zipRepository->getArchive()->extractTo(CatchAdmin::getModulePath($title));

        $this->installWithTitle($title);

        File::delete($zip);
    }
}

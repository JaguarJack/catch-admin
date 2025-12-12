<?php

declare(strict_types=1);

namespace Catch\PluginInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class PluginInstaller extends LibraryInstaller
{
    /**
     * 类型到安装路径的映射
     * 
     * @var array<string, string>
     */
    protected array $typePathMap = [
        'catchadmin-plugin' => 'vendor/catchadmin/plugins',
    ];

    public function __construct(IOInterface $io, Composer $composer)
    {
        parent::__construct($io, $composer);
    }

    /**
     * 判断是否支持该包类型
     */
    public function supports(string $packageType): bool
    {
        return isset($this->typePathMap[$packageType]);
    }

    /**
     * 获取包的安装路径
     */
    public function getInstallPath(PackageInterface $package): string
    {
        $type = $package->getType();

        if (!isset($this->typePathMap[$type])) {
            throw new \InvalidArgumentException(
                sprintf('Package type "%s" is not supported by CatchAdmin Plugin Installer.', $type)
            );
        }

        $basePath = $this->typePathMap[$type];
        $name = $this->getPackageBaseName($package);

        return $basePath . '/' . $name;
    }

    /**
     * 获取包的安装名称
     * 
     * 保留完整的 vendor/package 结构
     */
    protected function getPackageBaseName(PackageInterface $package): string
    {
        return $package->getPrettyName();
    }

    /**
     * 添加新的类型映射
     */
    public function addTypeMapping(string $type, string $path): self
    {
        $this->typePathMap[$type] = $path;
        return $this;
    }

    /**
     * 获取所有支持的类型
     * 
     * @return array<string>
     */
    public function getSupportedTypes(): array
    {
        return array_keys($this->typePathMap);
    }
}

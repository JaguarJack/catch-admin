<?php

declare(strict_types=1);

namespace Catch\PluginInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PluginInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        $installer = new PluginInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}

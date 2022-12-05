<?php

namespace Catch\Support;

use Illuminate\Support\Composer as LaravelComposer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException;

class Composer extends LaravelComposer
{
    protected bool $ignorePlatformReqs = false;


    /**
     * require package
     * @param string $package
     * @return string
     * @throws PhpVersionNotSupportedException
     */
    public function require(string $package): string
    {
        $this->checkPHPVersion();

        $command = ['require', $package];

        return $this->runCommand($command);
    }

    /**
     * require dev-package
     *
     * @param string $package
     * @return string
     * @throws PhpVersionNotSupportedException
     */
    public function requireDev(string $package): string
    {
        $this->checkPHPVersion();

        $command = ['require', '--dev', $package];

        return $this->runCommand($command);
    }


    /**
     * remove
     *
     * @param string $package
     */
    public function remove(string $package)
    {
        $this->runCommand([
            'remove', $package
        ]);
    }

    /**
     *
     * @param array $command
     * @return string
     */
    protected function runCommand(array $command): string
    {
        $command = array_merge($this->findComposer(), $command);

        if ($this->ignorePlatformReqs) {
            $command[] = '--ignore-platform-reqs';
        }

        $process = $this->getProcess($command);

        $process->run();

        return $process->getOutput();
    }

    /**
     *
     * @throws PhpVersionNotSupportedException
     * @return void
     */
    protected function checkPHPVersion(): void
    {
        $composerJson = json_decode(File::get(base_path().DIRECTORY_SEPARATOR.'composer.json'), true);

        $phpVersion = PHP_VERSION;

        $needPHPVersion = Str::of($composerJson['require']['php'])->remove('^');

        if (version_compare($phpVersion, $needPHPVersion, '<') && ! $this->ignorePlatformReqs) {
            throw new PhpVersionNotSupportedException("PHP $phpVersion 版本太低, 需要 PHP {$needPHPVersion}!如果想忽略版本要求, s可使用 {ignorePlatFormReqs} 方法然后安装");
        }
    }


    /**
     *
     * @return $this
     */
    public function ignorePlatFormReqs(): static
    {
        $this->ignorePlatformReqs = true;

        return $this;
    }
}

<?php

namespace Catch\Support\Module;

use Catch\Contracts\ModuleRepositoryInterface;
use Catch\Support\Composer;

/**
 * installer
 */
abstract class Installer
{
    /**
     * construct
     *
     * @param ModuleRepositoryInterface $moduleRepository
     */
    public function __construct(protected ModuleRepositoryInterface $moduleRepository)
    {
    }

    /**
     * module info
     *
     * @return array
     */
    abstract protected function info(): array;

    /**
     * migration
     *
     * @return string
     */
    abstract protected function migration(): string;

    /**
     * seed
     *
     * @return string
     */
    abstract protected function seeder(): string;

    /**
     * require packages
     *
     * @return void
     */
    abstract protected function requirePackages(): void;


    /**
     * remove packages
     *
     * @return void
     */
    abstract protected function removePackages(): void;

    /**
     * uninstall
     *
     * @return void
     */
    public function uninstall(): void
    {
        $this->moduleRepository->delete($this->info()['name']);


        $this->removePackages();
    }

    /**
     * invoke
     *
     * @return void
     */
    public function __invoke(): void
    {
        // TODO: Implement __invoke() method.
        $this->moduleRepository->create($this->info());

        // migration

        // seed

        $this->requirePackages();
    }

    /**
     * composer installer
     *
     * @return Composer
     */
    protected function composer(): Composer
    {
        return app(Composer::class);
    }
}

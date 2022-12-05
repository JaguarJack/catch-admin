<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

/**
 * creator
 */
abstract class Creator
{
    /**
     * @var string
     */
    protected string $ext = '.php';

    /**
     * @var string
     */
    protected string $module;

    /**
     * @var string
     */
    protected string $file;

    /**
     * create
     *
     * @return bool|string
     * @throws FileNotFoundException
     */
    public function create(): bool|string
    {
        return $this->put();
    }

     /**
      * the file which content put in
      *
      * @return string
      */
     abstract public function getFile(): string;

    /**
     * get content
     * @return string|bool
     */
    abstract public function getContent(): string|bool;

    /**
     * @return string|bool
     * @throws FileNotFoundException
     */
    protected function put(): string|bool
    {
        if (! $this->getContent()) {
            return false;
        }

        $this->file = $this->getFile();

        File::put($this->file, $this->getContent());

        if (File::exists($this->file)) {
            return $this->file;
        }

        throw new FileNotFoundException("create [$this->file] failed");
    }


    /**
     * set ext
     *
     * @param string $ext
     * @return $this
     */
    protected function setExt(string $ext): static
    {
        $this->ext = $ext;

        return $this;
    }


    /**
     * set module
     *
     * @param string $module
     * @return $this
     */
    public function setModule(string $module): static
    {
        $this->module = $module;

        return $this;
    }

    /**
     * get file
     *
     * @return string
     */
    public function getGenerateFile(): string
    {
        return $this->file;
    }
}

<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

/**
 * creator
 */
abstract class Creator
{
    use Format;

    protected string $ext = '.php';

    protected string $module;

    protected string $file;

    /**
     * create
     *
     * @throws FileNotFoundException
     */
    public function create(): bool|string
    {
        return $this->put();
    }

    /**
     * the file which content put in
     */
    abstract public function getFile(): string;

    /**
     * get content
     */
    abstract public function getContent(): string|bool|PhpFile;

    /**
     * @throws FileNotFoundException
     */
    protected function put(): string|bool
    {
        $content = $this->getContent();

        if (! $content) {
            return false;
        }

        if ($content instanceof PhpFile) {
            $printer = new PsrPrinter;
            $printer->setTypeResolving(false);
            $content = $printer->printFile($content);
        }

        $this->file = $this->getFile();

        File::put($this->file, $content);

        if (File::exists($this->file)) {
            return $this->file;
        }

        throw new FileNotFoundException("create [$this->file] failed");
    }

    /**
     * set ext
     *
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
     * @return $this
     */
    public function setModule(string $module): static
    {
        $this->module = $module;

        return $this;
    }

    /**
     * get file
     */
    public function getGenerateFile(): string
    {
        return $this->file;
    }
}

<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace Catch\Support\Zip;

use Exception;
use ZipArchive;

class ZipRepository
{
    private mixed $archive;

    /**
     * Construct with a given path
     *
     * @param $filePath
     * @param bool $create
     * @param $archive
     *
     * @return void
     * @throws Exception
     *
     */
    public function __construct($filePath, bool $create, $archive = null)
    {
        //Check if ZipArchive is available
        if (! class_exists('ZipArchive')) {
            throw new Exception('Error: Your PHP version is not compiled with zip support');
        }
        $this->archive = $archive ? $archive : new ZipArchive();

        $res = $this->archive->open($filePath, ($create ? ZipArchive::CREATE : 0));
        if ($res !== true) {
            throw new Exception("Error: Failed to open $filePath! Error: ".$this->getErrorMessage($res));
        }
    }

    /**
     * Add a file to the opened Archive
     *
     * @param $pathToFile
     * @param $pathInArchive
     */
    public function addFile($pathToFile, $pathInArchive): void
    {
        $this->archive->addFile($pathToFile, $pathInArchive);
    }

    /**
     * Add an empty directory
     *
     * @param $dirName
     */
    public function addEmptyDir($dirName): void
    {
        $this->archive->addEmptyDir($dirName);
    }

    /**
     * Add a file to the opened Archive using its contents
     *
     * @param string $name
     * @param $content
     */
    public function addFromString(string $name, $content): void
    {
        $this->archive->addFromString($name, $content);
    }

    /**
     * Remove a file permanently from the Archive
     *
     * @param string $pathInArchive
     */
    public function removeFile(string $pathInArchive): void
    {
        $this->archive->deleteName($pathInArchive);
    }

    /**
     * Get the content of a file
     *
     * @param string $pathInArchive
     *
     * @return string
     */
    public function getFileContent(string $pathInArchive): string
    {
        return $this->archive->getFromName($pathInArchive);
    }

    /**
     * Get the stream of a file
     *
     * @param string $pathInArchive
     *
     * @return bool
     */
    public function getFileStream(string $pathInArchive): bool
    {
        return $this->archive->getStream($pathInArchive);
    }

    /**
     * Will loop over every item in the archive and will execute the callback on them
     * Will provide the filename for every item
     *
     * @param $callback
     */
    public function each($callback): void
    {
        for ($i = 0; $i < $this->archive->numFiles; ++$i) {
            //skip if folder
            $stats = $this->archive->statIndex($i);
            if ($stats['size'] === 0 && $stats['crc'] === 0) {
                continue;
            }
            call_user_func_array($callback, [
                'file' => $this->archive->getNameIndex($i),
                'stats' => $this->archive->statIndex($i)
            ]);
        }
    }

    /**
     * Checks whether the file is in the archive
     *
     * @param $fileInArchive
     *
     * @return bool
     */
    public function fileExists($fileInArchive): bool
    {
        return $this->archive->locateName($fileInArchive) !== false;
    }

    /**
     * Sets the password to be used for decompressing
     * function named usePassword for clarity
     *
     * @param $password
     *
     * @return bool
     */
    public function usePassword($password): bool
    {
        return $this->archive->setPassword($password);
    }

    /**
     * Returns the status of the archive as a string
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->archive->getStatusString();
    }

    /**
     * Closes the archive and saves it
     */
    public function close(): void
    {
        @$this->archive->close();
    }

    /**
     * get error message
     *
     * @param $resultCode
     * @return string
     */
    private function getErrorMessage($resultCode): string
    {
        return match ($resultCode) {
            ZipArchive::ER_EXISTS => 'ZipArchive::ER_EXISTS - File already exists.',
            ZipArchive::ER_INCONS => 'ZipArchive::ER_INCONS - Zip archive inconsistent.',
            ZipArchive::ER_MEMORY => 'ZipArchive::ER_MEMORY - Malloc failure.',
            ZipArchive::ER_NOENT => 'ZipArchive::ER_NOENT - No such file.',
            ZipArchive::ER_NOZIP => 'ZipArchive::ER_NOZIP - Not a zip archive.',
            ZipArchive::ER_OPEN => 'ZipArchive::ER_OPEN - Can\'t open file.',
            ZipArchive::ER_READ => 'ZipArchive::ER_READ - Read error.',
            ZipArchive::ER_SEEK => 'ZipArchive::ER_SEEK - Seek error.',
            default => "An unknown error [$resultCode] has occurred.",
        };
    }
}

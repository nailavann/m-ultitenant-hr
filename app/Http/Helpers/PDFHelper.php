<?php

namespace App\Http\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PDFHelper
{
    private $file;
    private $directory;
    private $extension;
    private $fileSystem;

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension ?? 'pdf';
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return Filesystem
     */
    private function getFileSystem(): Filesystem
    {
        if (!$this->fileSystem) {
            /** @var FilesystemAdapter $fileSystem */
            $this->fileSystem = Storage::disk('public');
        }
        return $this->fileSystem;
    }

    /**
     * @param string $folder
     * @param string $ext
     * @param bool $name
     * @return string
     */
    private function createPath(string $folder, string $ext, bool $name = true): string
    {
        $now = Carbon::now();

        $collect = collect();
        $collect->push($folder);
        $collect->push($now->isoFormat('YYYY'));
        $collect->push($now->isoFormat('MM'));
        $collect->push($now->isoFormat('DD'));

        return $collect->implode('/') . '/' . ($name ? Str::random(25) . "." . $ext : '');
    }

    /**
     * @throws Throwable
     */
    protected function createPDF($file, $path): string
    {
        throw_unless($file, Exception::class, 'PDF is empty!');

        $fileSystem = $this->getFileSystem();
        throw_unless($fileSystem, Exception::class, 'File System problem!');
        throw_unless($fileSystem->put($path, base64_decode($file)), Exception::class, 'Could not save PDF!');

        return $path;
    }

    /**
     * @throws Throwable
     */
    protected function pdf(): string
    {
        $path = $this->createPath($this->getDirectory(), $this->getExtension());
        return $this->createPDF($this->file, $path);
    }

    /**
     * @throws Throwable
     */
    public function uploadPDF($pdfData, $operation): ?string
    {
        try {
            $this->setDirectory($operation);
            $this->setExtension('pdf');
            $this->setFile($pdfData);
            return $this->pdf();
        } catch (Throwable $e) {
            return null;
        }
    }
}

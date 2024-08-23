<?php

namespace App\Http\Helpers;


use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Throwable;

class ImageHelper
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
        return $this->extension ?? 'jpg';
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
    protected function createImage($file, $path): string
    {
        throw_unless($file, Exception::class, 'Image is empty!');

        $fileSystem = $this->getFileSystem();
        throw_unless($fileSystem, Exception::class, 'File System problem!');
        throw_unless($fileSystem->put($path, $file), Exception::class, 'Could not saved!');

        return $path;
    }


    /**
     * @throws Throwable
     */
    public function image(): string
    {
        $image = ImageManagerStatic::make($this->getFile())->encode($this->getExtension(), 80);
        $path = $this->createPath($this->getDirectory(), $this->getExtension());
        return $this->createImage($image, $path);
    }


    /**
     * @throws Throwable
     */
    public function uploadImage($image, $operation): ?string
    {
        try {
            $this->setDirectory($operation);
            $this->setExtension('jpg');
            $this->setFile($image);
            return $this->image();
        } catch (Throwable $e) {
            return null;
        }
    }
}


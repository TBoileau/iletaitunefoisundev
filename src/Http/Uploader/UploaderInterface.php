<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Http\Uploader;

use Symfony\Component\HttpFoundation\File\File;

interface UploaderInterface
{
    public function upload(File $file): string;
}

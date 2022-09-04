<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Http\Uploader;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

final class Uploader implements UploaderInterface
{
    public function __construct(private string $uploadsDir, private SluggerInterface $slugger)
    {
    }

    public function upload(File $file): string
    {
        $originalFilename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename)->lower()->toString();

        $filename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $file->guessExtension());

        $file->move($this->uploadsDir, $filename);

        return $filename;
    }
}

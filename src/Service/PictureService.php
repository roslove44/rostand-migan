<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function addFile(UploadedFile $file, ?string $folder = null, ?string $name = null)
    {
        $tmp = $file->getPathname();
        $ext = $file->guessExtension();
        $path = $this->parameterBag->get('images_directory');
        if (!$name) {
            $name = md5(uniqid(rand(), true)) . '.' . $ext;
        } else {
            $name = $name . '.' . $ext;
        }
        if ($folder) {
            $path = $path . $folder . '/';
        }

        try {
            $file->move($path, $name);
            return $path . $name;
        } catch (FileException $e) {
        }
    }
}

<?php
/**
 * Created by AliGhaleyan
 */

namespace AliGhale\FileManager\Types;

use AliGhale\FileManager\BaseType;

class Image extends BaseType
{
    protected $sizes = null;
    protected $thumb = null;


    /**
     * @inheritDoc
     */
    protected function handle($file)
    {
        $path = $this->getFullPath();
        $originalPath = $path . "original/";

        if ($file->move($originalPath, $this->getFileName())) {
            $this->createFileRow();
        }

        $this->resize($originalPath . $this->getFileName(), $path, $this->getFileName());

        return $this;
    }


    /**
     * resize image and return specific array of images
     *
     * @param $filePath
     * @param $uploadPath
     * @param $fileName
     * @return mixed
     */
    protected function resize($filePath, $uploadPath, $fileName)
    {
        if (is_null($this->getSizes())) {
            if ($sizes = $this->getConfig("sizes"))
                $this->setSizes($sizes);
            else
                $this->setSizes(["16", "24", "32", "64", "128"]);
        }

        if (is_null($this->getThumbSize())) {
            if (!$thumb = $this->getConfig("thumb"))
                $this->setThumbSize($thumb);
            else
                $this->setThumbSize("128");
        }

        $sizes = $this->getSizes();
        foreach ($sizes as $size) {
            $sizeUploadPath = $uploadPath . "{$size}/";
            if (!is_dir($sizeUploadPath)) mkdir($sizeUploadPath);
            $sizeName = $sizeUploadPath . $fileName;
            \Intervention\Image\Facades\Image::make($filePath)->fit($size, $size, function ($constraint) {
                $constraint->aspectRatio();
//                $constraint->upsize();
            })->save($sizeName);
        }

        $thumbUploadPath = $uploadPath . "thumb/";
        if (!is_dir($thumbUploadPath)) mkdir($thumbUploadPath);
        $thumbPath = $thumbUploadPath . $fileName;
        copy($uploadPath . "{$this->getThumbSize()}/" . $fileName, $thumbPath);

        return $this;
    }


    /**
     * set sizes
     *
     * @param array $sizes
     * @return $this
     */
    public function setSizes(array $sizes)
    {
        $this->sizes = $sizes;
        return $this;
    }


    /**
     * get current sizes
     *
     * @return array
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * set sizes
     *
     * @param $size
     * @return $this
     */
    public function setThumbSize($size)
    {
        $this->thumb = $size;
        return $this;
    }


    /**
     * get current sizes
     *
     * @return array
     */
    public function getThumbSize()
    {
        return $this->thumb;
    }
}
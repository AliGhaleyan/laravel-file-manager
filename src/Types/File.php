<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager\Types;

use AliGhale\FileManager\BaseType;

class File extends BaseType
{
    /**
     * @param \Illuminate\Http\File $file
     * @return BaseType
     */
    protected function handle($file): BaseType
    {
        if ($file->move($this->getStorageFolder($this->getUploadPath()), $this->getFileName())) {
            $this->createFileRow();
        }

        return $this;
    }
}

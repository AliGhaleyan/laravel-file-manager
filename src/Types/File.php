<?php
/**
 * Created by SERJIK
 */

namespace Serjik\FileManager\src\Types;

use Serjik\FileManager\BaseType;

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

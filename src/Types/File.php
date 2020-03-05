<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager\Types;

use AliGhale\FileManager\BaseType;
use AliGhale\FileManager\Models\File as FileModel;
use Illuminate\Support\Facades\File as FileFacade;

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


    protected function handleDelete(FileModel $file)
    {
        $path = $file->base_path . $file->file_name;
        if ($file->private) {
            $path = storage_path($path);
        } else {
            $path = public_path($path);
        }

        return FileFacade::delete($path);
    }
}

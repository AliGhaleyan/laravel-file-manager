<?php
/**
 * Created by SERJIK
 */

namespace Serjik\FileManager\src\Facades;


use Illuminate\Support\Facades\Facade;
use Serjik\FileManager\BaseType;

/**
 * Class File
 * @package Serjik\FileManager\src\Facades
 *
 * @method static BaseType useFileNameToUpload($status = true)
 * @method static upload($file)
 * @method static \Serjik\FileManager\src\Models\File getFile($name = null)
 * @method static BaseType setFile(File $file)
 * @method static BaseType setConfig(array $config)
 * @method static getConfig($name = null)
 * @method static BaseType setType($type)
 * @method static getType()
 * @method static BaseType setPath($path)
 * @method static getPath()
 * @method static getUploadPath()
 * @method static BaseType dateTimePrefix($value = true)
 * @method static getPrefix()
 * @method static getStorageFolder($src)
 * @method static BaseType setName(string $name)
 * @method static getName()
 * @method static BaseType setFormat(string $format)
 * @method static getFormat()
 * @method static BaseType isPrivate()
 * @method static BaseType isPublic()
 * @method static getFilePath($parameters = [])
 * @method static getFileName()
 */
class File extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Serjik\FileManager\src\File::class;
    }
}

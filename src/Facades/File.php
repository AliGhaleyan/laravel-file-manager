<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager\Facades;


use Illuminate\Support\Facades\Facade;
use AliGhale\FileManager\BaseType;

/**
 * Class File
 * @package AliGhale\FileManager\Facades
 *
 * @method static BaseType useFileNameToUpload($status = true)
 * @method static BaseType upload($file)
 * @method static \AliGhale\FileManager\Models\File getFile($name = null)
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
 * @method static BaseType setPrefix($prefix)
 * @method static getStorageFolder($src)
 * @method static BaseType setName(string $name)
 * @method static getName()
 * @method static BaseType setFormat(string $format)
 * @method static getFormat()
 * @method static BaseType isPrivate()
 * @method static BaseType isPublic()
 * @method static getFilePath($parameters = [])
 * @method static getFileName()
 * @method static BaseType fetchProperties(array $config = null)
 * @method static BaseType type($type = null)
 */
class File extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AliGhale\FileManager\File::class;
    }
}

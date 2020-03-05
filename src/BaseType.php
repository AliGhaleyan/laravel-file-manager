<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use AliGhale\FileManager\Models\File;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class BaseType
 * @package AliGhale\FileManager
 */
abstract class BaseType
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var File $file
     */
    protected $file = null;

    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var string $prefix
     */
    protected $prefix;

    /**
     * @var bool $dateTimePrefix
     */
    protected $dateTimePrefix = true;

    /**
     * @var bool $prefixPriority
     */
    protected $prefixPriority = true;

    /**
     * @var string|null $format
     */
    protected $format = null;

    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var bool $public
     */
    protected $public = true;

    /**
     * @var string $type
     */
    protected $type = "default";

    /**
     * @var string $storageFolder
     */
    protected $storageFolder = "public";

    /**
     * @var bool $useFileNameToUpload
     */
    protected $useFileNameToUpload = false;

    /**
     * @var static $instance
     */
    protected static $instance = null;


    /**
     * BaseDriver constructor.
     *
     * private construct for singleton
     */
    public function __construct()
    {
        //
    }


    /**
     * fetch and set from config (array)
     *
     * @param array|null $config
     * @return $this
     */
    public function fetchProperties(array $config = null)
    {
        if (is_null($config)) $config = $this->getConfig();

        if (isset($config['path'])) $this->setPath($config['path']);
        if (isset($config['name'])) $this->setName($config['name']);
        if (isset($config['format'])) $this->setFormat($config['format']);
        if (isset($config['private'])) {
            if ($config['private'] == true)
                $this->isPrivate();
            elseif ($config['private'] == false)
                $this->isPublic();
        }
        if (isset($config['date_time_prefix'])) $this->dateTimePrefix($config['date_time_prefix']);
        if (isset($config['use_file_name_to_upload']) && $config['use_file_name_to_upload'])
            $this->useFileNameToUpload();

        return $this;
    }


    /**
     * generate and unique & random name
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomName(int $length = 15)
    {
        do {
            $randomName = Str::random($length);
            $check = File::query()
                ->where("name", $randomName)
                ->first();
        } while (!empty($check));

        return $randomName;
    }


    /**
     * if you say this method when upload file with original name
     *
     * @param bool $status
     * @return $this
     */
    public function useFileNameToUpload($status = true)
    {
        $this->useFileNameToUpload = $status;
        return $this;
    }


    /**
     * first get passed file and fetch name and format from original name
     * and if not set these when set
     * then return handle method
     *
     * @param $file
     * @return mixed
     */
    public function upload($file)
    {
        $nameSplit = explode('.', $file->getClientOriginalName());
        $fileName = $nameSplit[0];
        $format = $nameSplit[1];
        if (!$this->getName()) {
            if ($this->useFileNameToUpload) {
                $this->setName($fileName);
            } else {
                $this->setName($this->generateRandomName());
            }
        }

        if (is_null($this->getFormat())) $this->setFormat($format);

        return $this->handle($file);
    }


    /**
     * create a new file row in db
     *
     * @param null $name
     * @return mixed
     */
    protected function createFileRow($name = null)
    {
        $file = File::create([
            "name"      => $name ?? $this->getName() ?? $this->generateRandomName(),
            "file_name" => $this->getFileName(),
            "type"      => $this->getType(),
            "base_path" => $this->getUploadPath(),
            "format"    => $this->getFormat(),
            "private"   => $this->public ? false : true,
        ]);
        $this->setFile($file);
        return $file;
    }


    /**
     * if has $this->file and is null name return this
     * else get file by name
     *
     * @param null $name
     * @return Builder|Model|\Illuminate\Http\File|File
     * @throws InternalErrorException
     */
    public function getFile($name = null)
    {
        if (is_null($name) && !is_null($this->file) && $this->file instanceof File) return $this->file;

        if (is_null($name)) throw new InternalErrorException("file name not valid!");

        return File::query()
            ->where("name", $name)
            ->first();
    }


    /**
     * set $this->file
     *
     * @param File $file
     * @return $this
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        return $this;
    }


    public function delete($filename = null)
    {
        /** @var File $file */
        $file = $this->getFile($filename);

        if ($file) {
            $flag = $this->handleDelete($file);
            $file->delete();
        }

        return $flag ?? true;
    }


    /**
     * handling upload file
     *
     * @param $file
     * @return BaseType
     */
    abstract protected function handle($file);


    /**
     * handle delete file
     *
     * @param File $file
     * @return mixed
     */
    abstract protected function handleDelete(File $file);


    /**********     Getters & Setters    **********/

    /**
     * set config
     *
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }


    /**
     * get config
     * if pass name:
     *  check exists that
     *  if exists then return the config
     *  else if not exists name return false
     *
     * else or not pass name:
     *  return the all configs
     *
     * @param null $name
     * @return array|bool|mixed
     */
    public function getConfig($name = null)
    {
        $config = $this->config;
        if (is_null($name)) return $config;
        $find = $config[$name];
        if (!isset($find)) return false;
        return $find;
    }


    /**
     * set type
     * this type is one of types in filemanager.php (module config)
     *
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * get the current type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * set upload path
     *
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }


    /**
     * get upload path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * get full path
     *
     * @return string
     */
    public function getFullPath()
    {
        return $this->getStorageFolder($this->getUploadPath());
    }


    /**
     * get upload location path
     * we append the prefix
     * if the dateTimePrefix property is true also append this {$year}/{$month}/{$day}/
     *
     * @return string
     */
    public function getUploadPath()
    {
        $path = $this->getPath();
        $prefix = $this->getPrefix() ?? "";

        /** Check and set dateTimePrefix */
        if ($this->dateTimePrefix) {
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;
            $day = $now->day;
            $prefix .= "{$year}/{$month}/{$day}/";
        }

        return $path . $prefix;
    }


    /**
     * set dateTimePrefix the value
     * and default is true
     * if this is true so append datetime prefix to upload path
     * else dont do
     *
     * @param bool $value
     * @return $this
     */
    public function dateTimePrefix($value = true)
    {
        $this->dateTimePrefix = $value;
        return $this;
    }


    /**
     * get set prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


    /**
     * get set prefix
     *
     * @param $prefix
     * @return string
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }


    /**
     * check storage path
     *
     * @param $src
     *
     * @return string
     */
    protected function getStorageFolder($src)
    {
        if ($this->storageFolder == "storage")
            return storage_path($src);
        if ($this->storageFolder == "public")
            return public_path($src);
        return public_path($src);
    }


    /**
     * you can set file name for upload
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * get the current name for upload file
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * set format for upload
     * like: ['png', 'jpg', 'jpeg', 'xls',]
     *
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
        return $this;
    }


    /**
     * get the current format
     *
     * @return string|null
     */
    public function getFormat()
    {
        return $this->format;
    }


    /**
     * if call this method
     * set storageFolder property to storage
     * and the public property to false
     *
     * @return $this
     */
    public function isPrivate()
    {
        $this->storageFolder = "storage";
        $this->public = false;
        return $this;
    }


    /**
     * if call this method
     * set storageFolder property to public
     * and the public property to true
     *
     * @return $this
     */
    public function isPublic()
    {
        $this->storageFolder = "public";
        $this->public = true;
        return $this;
    }


    /**
     * get file path like: /path/prefix/filename.format
     *
     * @param array $parameters
     * @return string
     */
    public function getFilePath()
    {
        return $this->getUploadPath() . $this->getFileName();
    }


    /**
     * get file name like: current name.format
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getName() . '.' . $this->getFormat();
    }
}

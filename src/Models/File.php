<?php
/**
 * Created by SERJIK
 */

namespace Serjik\FileManager\src\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Class File
 * @package Serjik\FileManager\src\Modelsid
 *
 * @property $id
 * @property $name
 * @property $path
 * @property $private
 * @property $isPrivate
 * @property $isPublic
 * @property $type
 * @property $created_at
 */
class File extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "id",
        "name",
        "path",
        "private",
        "type",
        "created_at",
    ];


    /**
     * is private this file
     *
     * @return bool
     */
    public function getIsPrivateAttribute()
    {
        return $this->private ? true : false;
    }


    /**
     * is public this file
     *
     * @return bool
     */
    public function getIsPublicAttribute()
    {
        return $this->private ? false : true;
    }


    /**
     * generate the link for download file
     * this link has expire time
     *
     * @return string
     */
    public function generateLink()
    {
        $config = filemanager_config();
        $secret = "";

        if (isset($config['secret'])) {
            $secret = $config['secret'];
        }

        if (isset($config['download_link_expire'])) {
            $expireTime = (int) $config['download_link_expire'];
        }

        /** @var int $expireTime */
        $timestamp = Carbon::now()->addMinutes($expireTime)->timestamp;
        $hash = Hash::make($secret . $this->id . request()->ip() . $timestamp);

        return "/api/filemanager/download/$this->id?mac=$hash&t=$timestamp";
    }


    /**
     * download the selected file
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download()
    {
        if (!$this->private) {
            $path = public_path($this->path);
        } else {
            $path = storage_path($this->path);
        }
        return response()->download($path);
    }
}

<?php
/**
 * Created by SERJIK
 */

namespace AliGhale\FileManager\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use AliGhale\FileManager\Models\File;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController
{
    /**
     * check timestamp and return download
     *
     * @param $id
     * @return BinaryFileResponse
     * @throws InternalErrorException
     */
    public function download($file)
    {
        /** @var File $file */
        $file = File::query()
            ->where("id", $file)
            ->orWhere("name", $file)
            ->firstOrFail();

        $config = filemanager_config();

        if ($file->isPublic) {
            return $file->download();
        } else {
            $secret = "";
            if ($config['secret']) {
                $secret = $config['secret'];
            }

            $hash = $secret . $file->id . request()->ip() . request('t');

            if ((Carbon::createFromTimestamp(request('t')) > Carbon::now()) &&
                Hash::check($hash, request('mac'))) {
                return $file->download();
            } else {
                throw new InternalErrorException("link not valid");
            }
        }
    }
}

<?php
/**
 * Created by SERJIK
 */

namespace Serjik\FileManager\src\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Serjik\FileManager\src\Models\File;
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
    public function download($id)
    {
        /** @var File $file */
        $file = File::query()
            ->findOrFail($id);

        $config = filemanager_config();

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

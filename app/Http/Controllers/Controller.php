<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function uploadFile($folderName, $file, $titleSlug)
    {
        $folderPath = $folderName . '/' .  $titleSlug;

        if (!file_exists(public_path($folderPath))) {
            mkdir(public_path($folderPath), 0777, true);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $folderPath . '/' . $fileName;

        $file->move(public_path($folderPath), $fileName);

        return $filePath;
    }

    public function updateFile($folderName, $newFile, $oldFilePath, $titleSlug)
    {
        if (!$newFile) {
            return $oldFilePath;
        }

        $newFilePath = $this->uploadFile($folderName, $newFile, $titleSlug);

        if ($newFilePath && $oldFilePath && file_exists(public_path($oldFilePath))) {
            unlink(public_path($oldFilePath));
        }

        return $newFilePath;
    }
}

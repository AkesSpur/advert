<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait FileUploadTrait
{
    /**
     * Handle file upload or update.
     *
     * @param Request $request
     * @param string $inputName The name of the file input in the request.
     * @param string|null $oldPath The path to the old file, if updating.
     * @param string $uploadPath The directory to upload the new file to.
     * @return string|null The path to the uploaded file, or null if no file was uploaded.
     */
    public function updateImage(Request $request, string $inputName, ?string $oldPath = null, string $uploadPath): ?string
    {
        if ($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;

            $file->move(public_path($uploadPath), $fileName);

            // Delete the old file if it exists and is different from the new one
            if ($oldPath && File::exists(public_path($oldPath))) {
                File::delete(public_path($oldPath));
            }
            return $uploadPath . '/' . $fileName;
        }

        return $oldPath; // Return old path if no new file is uploaded
    }

    /**
     * Remove file.
     *
     * @param string $filePath
     * @return void
     */
    public function removeImage(string $filePath): void
    {
        if (File::exists(public_path($filePath))) {
            File::delete(public_path($filePath));
        }
    }
}
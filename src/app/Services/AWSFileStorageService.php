<?php
namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AWSFileStorageService {
    /**
     * Store a file to storage
     * 
     * @param mixed $file
     * @param string $filename
     * @param int $id
     */
    public function save($file, string $filename) {
        $success = Storage::disk('s3')->put($filename, $file, 'public');

        return $success;
    }

    /**
     * Get disk url
     * 
     * @param string $disk
     * 
     * @return string
     */
    public function getDiskURL(string $disk): string {
        return 'https://fileppdbsumsel.sgp1.digitaloceanspaces.com/';
    }


    /**
     * Get url by filename
     * 
     * @param mixed $filename
     * @param int $id
     * 
     * @return string
     */
    public function getUrl($filename): string {
        return Storage::disk('s3')->url($filename);
    }
}

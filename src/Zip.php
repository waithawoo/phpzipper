<?php

namespace WaiThaw\PhpZipper;

use \ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Zip
{
    private $zip;

    public function __construct()
    {
    }

    public function createFromFiles(string $zipfile, $files, string $password = null, $flag = ZipArchive::CREATE | ZipArchive::OVERWRITE)
    {
        try {
            $zip = self::openZip($zipfile, $flag);
            if (is_array($files)) {
                foreach ($files as $file) {
                    // $zip->addFile($file, substr($file,strrpos($file,'/')+1));
                    $zip->addFile($file, basename($file));
                    if($password != null){
                        $zip->setEncryptionName(basename($file), ZipArchive::EM_AES_256, $password);
                    }
                }
            }else{
                $zip->addFile($files, basename($files));
                    if($password != null){
                        $zip->setEncryptionName(basename($files), ZipArchive::EM_AES_256, $password);
                    }
            }
            
            $zip->close();
            
            return $zipfile;
        } catch (\Exception $e) {
            throw new ZipException($e->getMessage());
        }
    }

    public function createFromDir(string $zipfile, string $directory, string $password = null, $flag = ZipArchive::CREATE | ZipArchive::OVERWRITE)
    {   
        try {
            $pathInfo = pathinfo($directory);
            $parentPath = $pathInfo['dirname'];
            $dirName = $pathInfo['basename'];

            $zip = self::openZip($zipfile, $flag);
            self::dir2zip($zip, $directory, $password);

            $zip->close();

            return $zipfile;
        } catch (\Exception $e) {
            throw new ZipException($e->getMessage());
        }
    }

    public function extractTo(string $zipfile, string $destinationPath, string $password = null)
    {
        try {
            $zip = self::openZip($zipfile);
        
            if($password != null && $zip->setPassword($password)){
                $zip->extractTo($destinationPath);
            }else{
                $zip->extractTo($destinationPath);
            }       
            if($zip->status != 0){
                throw new ZipException("Error : ".$zip->getStatusString());
            }
            $zip->close();

            return true;
        } catch (\Exception $e) {
            throw new ZipException($e->getMessage());
        }
    }

    public function download($unlink = null)
    {
        try {
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="'.basename($this->zipfile).'"');
            header("Content-length: " . filesize($this->zipfile));
            header("Pragma: no-cache");
            header("Expires: 0");
            ob_clean();
            flush();
            readfile($this->zipfile);
            if($unlink == 'delete'){
                unlink($this->zipfile);
            }
            exit;
        } catch (\Exception $e) {
            throw new ZipException($e->getMessage());
        }
    }

    private static function dir2zip($zip, $directory, $password = null)
    {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file)
        {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($directory)+1);

            if (!$file->isDir())
            {
                $zip->addFile($filePath, $relativePath);
                if($password != null){
                    $zip->setEncryptionName($relativePath, ZipArchive::EM_AES_256, $password);
                }
            }else {
                if($relativePath !== false){
                    $zip->addEmptyDir($relativePath);
                    if($password != null){
                        $zip->setEncryptionName($relativePath, ZipArchive::EM_AES_256, $password);
                    }
                }
            }
        }
    }

    private static function openZip($zipfile, $flags = null)
    {
        $zip = new ZipArchive();
        $open_zip = $zip->open($zipfile, $flags);
        
        if ($open_zip !== true) {
            throw new ZipException("Error : Can't Open Zip");
        }
        return $zip;
    }
}

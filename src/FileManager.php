<?php 
namespace Src;

use CoffeeCode\Uploader\File;
use CoffeeCode\Uploader\Send;
use CoffeeCode\Uploader\Image;

class FileManager
{
    private $path;
    private $paths = [];
    private $Error;

    /**
     * Get the value of uploadedPath
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of uploadedPath
     *
     * @return  self
     */
    public function setPath($uploadedPath)
    {
        $this->path = $uploadedPath;

        return $this;
    }

    /**
     * Get the value of uploadedsPath
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Set the value of uploadedsPath
     *
     * @return  self
     */
    public function setPaths($uploadedsPath)
    {
        array_push($this->paths, $uploadedsPath);
    }

    /**
     * Get the value of Error
     */
    public function getError()
    {
        return $this->Error;
    }

    /**
     * Set the value of Error
     *
     * @return  self
     */
    public function setError($Error)
    {
        $this->Error = $Error;

        return $this;
    }
    /**
     * Upload  image file
     *
     * @param [type] $image
     * @param [type] $dirFolder
     *  [type] $size
     * @return void
     */
    public function storageAs(string $folder = "public/storage", bool $single = true, int $size=1920)
    {
        $files = $_FILES;
        $upload = new Image($folder, 'images');
        if ($single == true) {
            //if is single image
            $file = $files['photo'];
            if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
                $this->setError('The image type is invalid '.$file['name'].'or the input field name is not photo...');
                return false;
            } else {
                # upload image
                $uploaded = $upload->upload($file, pathinfo($file['name'], PATHINFO_FILENAME), $size);
                if ($uploaded) {
                    $this->setPath($uploaded);
                    return true;
                }
                $this->setError('IS not possible to save ' . $file['name']." change permition to folder ".$folder)."or change de input name to photo...";
                return false;
            }
        }else {
            //multiple upload
            $images = $files['photos'];
            for ($i = 0; $i < count($images['type']); $i++) {
                foreach (array_keys($images) as $keys) {
                    $imageFiles[$i][$keys] = $images[$keys][$i];
                }
            }
            foreach ($imageFiles as $file) {
                if (empty($file['type'])) {
                    $this->setError("Please  select an image file ou chage input field name to photos...");
                    return false;
                } elseif (!in_array($file['type'], $upload::isAllowed())) {
                    $this->Error .= "The file " . $file['name'] . " is not an image file \n";
                }
            }

            if (empty($this->Error)) {
                foreach ($imageFiles as $file) {
                    $uploaded = $upload->upload($file, pathinfo($file['name'], PATHINFO_FILENAME), $size);
                    $this->setPaths($uploaded);
                    if (!$uploaded) {
                        $this->Error .= "The file " . $file['name'] . " is not uploaded\n"; 
                    }
                }
                return true;
            } else {
                $this->setError('The image type is invalid '.$file['name'].'or the input field name is not photo...');
                return false;
            }
            
        }
        
    }
   
    /**
     * Upload single file (pdf tar zar zip rar)
     *
     * @param [type] $image
     *  [type] $dirFolder
     *  [type] $size
     * @return void
     */
    public function saveFile( string $folder = "public/storage", int $size)
    {
        $files = $_FILES;
        $upload = new File($folder, 'files');
            $file = $files['file'];
            if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
                $this->setError('The file type is invalid');
                return false;
            } else {
                # upload
                $uploaded = $upload->upload($file, pathinfo($file['name'], PATHINFO_FILENAME), $size);
                if ($uploaded) {
                    $this->setPath($uploaded);
                    return true;
                }
                $this->setError('IS not possible to save ' . $file['name']."change input field name to file or change permition to folder ".$folder);
                return false;
            }
    }
    /**
     * upload single videos and musics and others
     *
     * @param [type] $media
     *  [type] $folder
     *  [type] $size
     * @return void
     */
    public function saveOther(string $folder = "public/storage", int $size)
    {
        $files = $_FILES;
        $upload = new Send($folder, 'others', [
            "audio/mpeg",
            "audio/mp3",
            "video/mp4"
        ],['mpeg','mp3','mp4']);
        
            $file = $files['file'];
            if (empty($file['type']) || !in_array($file['type'], $upload::isAllowed())) {
                $this->setError('The media type is invalid '.$file['name']);
                return false;
            } else {
                # code...
                $uploaded = $upload->upload($file, pathinfo($file['name'], PATHINFO_FILENAME), $size);
                if ($uploaded) {
                    $this->setPath($uploaded);
                    return true;
                }
                $this->setError('IS not possible to save ' . $file['name']." change permition to folder ".$folder);
                return false;
            }
    }
    /**
     * delete files acording to the location given.
     *
     * @param string $fileName
     * @return void
     */
    public function deleteFile( string $fileName)
    {
 
        if (unlink($fileName)) {
            return true;
        } else {
            $this->setError('Ooops...Error to delete ' . $fileName);
            return false;
        }
    }

}
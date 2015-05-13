<?php
/**
 * @name FilesHandler
 * @author Darius Matulionis <darius@matulionis.lt>
 * @since : 2012-02-23, 14.40.09
 */

require_once 'config.php';

class FilesHandler{
    
    protected $allowed_extensions = array("jpg","jpeg","png","gif");
    protected $directories;
    public function __construct() {
        
    }
    
    /**
     * Get basic information about image
     * @param string $file path to the file
     * @return array
     */
    public function getFileInfo($file){
        $fileInfo = array();
        $imgInfo = getimagesize($file);
        if($imgInfo){
            $fileInfo['width'] = $imgInfo[0];
            $fileInfo['height'] = $imgInfo[1];
            $fileInfo['name'] = basename($file);
        }
        return $fileInfo;
    }
    
    /**
     * Get directories recursivly
     * @param string $dir
     * @return array 
     */
    public function getDirectoryList($dir = UPLOADS_PATH){
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir"){
                        $this->directories[] = str_replace(UPLOADS_PATH."/","",$dir . "/" . $object);
                        $this->getDirectoryList($dir . "/" . $object);
                    }
                }
            }
        }
        return $this->directories;
    }
    
    /**
     * Delete directory recursivly
     * @param string $dir directory path
     */
    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object); else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
    
    /**
     * Show image croped from center
     * @param string $fileName
     * @param int $width Image Width
     * @param int $height Image Height 
     */
    public function thumbCropFromCenter($fileName,$width = 160,$height = 110){
        require_once 'thumb/ThumbLib.inc.php';  
        $thumb = PhpThumbFactory::create(UPLOADS_PATH.DIRECTORY_SEPARATOR.$fileName);
        $thumb->cropFromCenter($width, $height);
        $thumb->show();
        //die();
    }
    
    /**
     * Get files and directories
     * @param string $directory
     * @param boolean $json
     * @return array|json string 
     */
    public function getFilesAndDirectories($directory = null, $json = false){
        
        if(!is_dir(UPLOADS_PATH)){
            return false;
        }
        
        $scan_directory = UPLOADS_PATH;
        
        if($directory){
            $scan_directory .= DIRECTORY_SEPARATOR.$directory;
        }
        
        $filesAndDirectories = array();
        
        foreach (scandir($scan_directory) as $key => $value) {
            if($value != "." && $value != ".."){
                $fileName = $scan_directory.DIRECTORY_SEPARATOR.$value;
                if(is_dir($fileName)){
                    $filesAndDirectories['directories'][] = $value;
                }elseif(is_file($fileName)){
                    $fileInfo = pathinfo($fileName);
                    if(in_array(strtolower($fileInfo['extension']), $this->allowed_extensions)){
                         $filesAndDirectories['files'][] = $value;
                        /*
                        $imgInfo = getimagesize($scan_directory.DIRECTORY_SEPARATOR.$value);
                        if($imgInfo[0] < $imgInfo[1]){
                            $filesAndDirectories['files']['v'][] = $value;
                        }else{
                            $filesAndDirectories['files']['h'][] = $value;
                        }*/
                    }
                }
            }
        }
        
        if($json){
            $filesAndDirectories['base_url'] = UPLOADS_URL;
            return json_encode($filesAndDirectories);
        }
        
        return $filesAndDirectories;
    }
    
    public function getAllowedExtensions(){
        return $this->allowed_extensions;
    }
    
    
    
    // Helper functions for HTML5 upload

    public function exit_status($str){
            echo json_encode(array('status'=>$str));
            exit;
    }

    public function get_extension($file_name){
            $ext = explode('.', $file_name);
            $ext = array_pop($ext);
            return strtolower($ext);
    }
    
}

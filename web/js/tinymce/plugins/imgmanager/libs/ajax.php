<?php

/**
 * @name Ajax Rquests
 * @copyright Darius Matulionis
 * @author Darius Matulionis <darius@matulionis.lt>
 */

//UPLOADIFY SESSION AUTH
//!!!! CHANGE THIS IF NEDED!!!!!!
if (!empty($_FILES) && isset($_FILES['Filedata']) && $_REQUEST['hash']) {
    require 'EncriptDecript.php';
    $ed = new EncriptDecript();
    $session_id = $ed->decript($_REQUEST['hash']);
    $_COOKIE['PHPSESSID'] = $session_id;
    session_id($session_id);
    session_start();
    $_SESSION['user_auth'] = true;
}

require_once 'config.php';
require_once 'FilesHandler.php';
$filesHandler = new FilesHandler();


//FILES UPLOAD UPLOADIFY
if (!empty($_FILES) && isset($_FILES['Filedata'])) {
    $tempFile = $_FILES['Filedata']['tmp_name'];

    $sub_dircetory = null;
    if ($_REQUEST['dir']) {
        $sub_dircetory = $_REQUEST['dir'] . "/";
    }

    $targetPath = UPLOADS_PATH . DIRECTORY_SEPARATOR . $sub_dircetory;

    $targetFile = str_replace('//', '/', $targetPath) . $_FILES['Filedata']['name'];

    move_uploaded_file($tempFile, $targetFile);
    echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $targetFile);
    exit;
}

//Basic ajax requests
if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
    //Get Image Information
    if ($_GET['getImageInformation'] && $_GET['url']) {
        $filePath = str_replace(UPLOADS_URL, "", $_GET['url']);
        $file = UPLOADS_PATH . $filePath;
        if (file_exists($file)) {
            $info = $filesHandler->getFileInfo($file);
            $info['web_base_url'] = "/" . WEB_BASE . $filePath;
            $info['full_file_path'] = UPLOADS_PATH . $filePath;
            $info['file_path'] = str_replace($info['name'], "", UPLOADS_PATH . $filePath);
        }

        if ($info) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode($info);
        }
        exit;
    }

    //Add directory
    if ($_GET['addDirectory'] && $_GET['folder']) {
        $dir = UPLOADS_PATH;
        if (isset($_GET['dir']) && !empty($_GET['dir']) && is_string($_GET['dir']) && is_dir($dir . DIRECTORY_SEPARATOR . $_GET['dir'])) {
            $dir = $dir . DIRECTORY_SEPARATOR . $_GET['dir'];
        }

        if (is_string($_GET['folder']))
            mkdir($dir . "/" . $_GET['folder'], 0755);
        exit;
    }

    //Get directory List
    if ($_GET['getDirectoryList']) {
        $directories = array();
        $dirs = $filesHandler->getDirectoryList();
        foreach ($dirs as $dir) {
            $split = explode("/", $dir);
            $margin = "";
            for ($i = 1; $i <= count($split); $i++)
                $margin .= "...";
            $directories[$dir] = $margin . array_pop($split);
        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($directories);
        exit;
    }

    //Get files and directories
    if ($_GET['getFilesAndDirectories'] && $_GET['dir']) {

        if ($_GET['dir'] == "null" || $_GET['dir'] == "../") {
            $_GET['dir'] = null;
        }

        $filesAndDirectories = $filesHandler->getFilesAndDirectories($_GET['dir'], true);

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo $filesAndDirectories;
        exit;
    }

    //Delete File
    if ($_GET['deleteFile'] && $_GET['file']) {
        $file = UPLOADS_PATH . str_replace(UPLOADS_URL, "", $_GET['file']);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    //Delete folder
    if ($_GET['deleteFolder'] && $_GET['folder']) {
        $folder = UPLOADS_PATH . DIRECTORY_SEPARATOR . str_replace(UPLOADS_URL, "", $_GET['folder']);
        $filesHandler->rrmdir($folder);
    }
} else {

    //CROP IMG
    if ($_GET['cropImg'] && $_POST["imgData"]) {

        $width = $_POST['cW'];
        $height = $_POST['cH'];
        $x = $_POST['cX'];
        $y = $_POST['cY'];
        $jpeg_quality = 90;
        $src = $_POST['imgData']['full_file_path'];
        $output_filename = $_POST['imgData']['full_file_path'];
        if (!$_POST['overwrite']) {
            $fileInfo = pathinfo($output_filename);
            $output_filename = $_POST['imgData']['file_path'] . substr(md5(microtime()), 0, 10) . "." . strtolower($fileInfo['extension']);
        }

        $formatInfo = getimagesize($_POST['imgData']['full_file_path']);
        $mimeType = isset($formatInfo['mime']) ? $formatInfo['mime'] : null;
        $format = null;
        switch ($mimeType) {
            case 'image/gif':
                $format = 'GIF';
                break;
            case 'image/jpeg':
                $format = 'JPG';
                break;
            case 'image/png':
                $format = 'PNG';
                break;
            default:
                die("ERROR IMG FORMAT NOT SUPPORTED");
        }

        $img_r = null;
        switch ($format) {
            case 'GIF':
                $img_r = imagecreatefromgif($src);
                break;
            case 'JPG':
                $img_r = imagecreatefromjpeg($src);
                break;
            case 'PNG':
                $img_r = imagecreatefrompng($src);
                break;
        }

        if ($img_r) {
            $dst_r = ImageCreateTrueColor($width, $height);

            imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $width, $height, $width, $height);

            switch ($format) {
                case 'GIF':
                    imagegif($dst_r, $output_filename);
                    break;
                case 'JPG':
                    imagejpeg($dst_r, $output_filename, $jpeg_quality);
                    break;
                case 'PNG':
                    imagepng($dst_r, $output_filename);
                    break;
            }
        }
        exit;
    }


    // FILES UPLOAD HTML5
    if (array_key_exists('pic', $_FILES) && $_FILES['pic']['error'] == 0) {

        $pic = $_FILES['pic'];

        if (!in_array($filesHandler->get_extension($pic['name']), $filesHandler->getAllowedExtensions())) {
            $filesHandler->exit_status('Only ' . implode(',', $filesHandler->getAllowedExtensions()) . ' files are allowed!');
        }

        // Move the uploaded file from the temporary 
        // directory to the uploads folder:
        $sub_dircetory = null;
        if (isset($_GET['directory']))
            $sub_dircetory = $_GET['directory'];

        $file = UPLOADS_PATH . DIRECTORY_SEPARATOR . $sub_dircetory  . DIRECTORY_SEPARATOR .  $pic['name'];
        if (move_uploaded_file($pic['tmp_name'], $file)) {
            $filesHandler->exit_status('File was uploaded successfuly!');
        }
    }

    $filesHandler->exit_status('Something went wrong with your upload!');
}

exit;
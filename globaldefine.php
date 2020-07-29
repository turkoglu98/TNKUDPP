<?php
ob_start();
session_start();
include("vt.php");
require("./mailler/class.phpmailer.php");
define('vt_kullanici', 'root');
define('vt_sifre', '');
define('vt_host', 'localhost');
define('vt', 'fileshare');
define('files_root', getcwd().'/files');
define('register_quota',200*1024*1024); // 200mb

$vt = new vt(vt_kullanici, vt_sifre, vt, vt_host);
date_default_timezone_set('Europe/Istanbul');

function sizetoread($size)
{
    if ($size >= 1073741824) {
      $fileSize = round($size / 1024 / 1024 / 1024,1) . 'GB';
    } elseif ($size >= 1048576) {
        $fileSize = round($size / 1024 / 1024,1) . 'MB';
    } elseif($size >= 1024) {
        $fileSize = round($size / 1024,1) . 'KB';
    } else {
        $fileSize = $size . ' byte';
    }
    return $fileSize;
}
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
   
}
function isValidMd5($md5 ='') {
  return strlen($md5) == 32 && ctype_xdigit($md5);
}

function randomPassword() {
    $alphabet = "0123456789abcdefghijklmnopqrstuwxyz0123456789abcdefghijklmnopqrstuwxyz0123456789";
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}
function getRealIpAddr()  
{  
    if (!empty($_SERVER['HTTP_CLIENT_IP']))  
    {  
        $ip=$_SERVER['HTTP_CLIENT_IP'];  
    }  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
      
    {  
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
    else 
    {  
        $ip=$_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
} 

class FileFinder
{
    private $onFound;

    private function __construct($path, $onFound, $maxDepth)
    {
        // onFound gets called at every file found
        $this->onFound = $onFound;
        // start iterating immediately
        $this->iterate($path, $maxDepth);
    }

    private function iterate($path, $maxDepth)
    {
        $d = opendir($path);
        while ($e = readdir($d)) {
            // skip the special folders
            if ($e == '.' || $e == '..') { continue; }
            $absPath = "$path/$e";
            if (is_dir($absPath)) {
                // check $maxDepth first before entering next recursion
                if ($maxDepth != 0) {
                    // reduce maximum depth for next iteration
                    $this->iterate($absPath, $maxDepth - 1);
                }
            } else {
                // regular file found, call the found handler
                call_user_func_array($this->onFound, array($absPath));
            }
        }
        closedir($d);
    }

    // helper function to instantiate one finder object
    // return value is not very important though, because all methods are private
    public static function find($path, $onFound, $maxDepth = 0)
    {
        return new self($path, $onFound, $maxDepth);
    }
}

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

?>
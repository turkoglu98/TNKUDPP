<?php
include("./globaldefine.php");
if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$quota_used = $vt->veri('SELECT Sum(file_size) FROM files WHERE upload_by="'.$user.'"');
$quota_total = intval ($vt->veri('SELECT quota FROM users WHERE username="'.$user.'"'));

if(isset($_FILES['dosya'])){
$size = $_FILES['dosya']['size'];
$quota_used = $quota_used+$size;
if($quota_total != 0){
if($quota_used > $quota_total){
	echo "2";
	exit();
}
}
$name = basename($_FILES['dosya']['name']);
$name = htmlspecialchars(strip_tags(urldecode(addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($name))))))));
$notallowed = array('php', 'php5','php7', 'html','cmd','exe','bat','sh','');
$ext = pathinfo($name, PATHINFO_EXTENSION);
if (in_array($ext, $notallowed)) {
    echo "0";
    exit();
}
$rfile = rand(100000,9999999);
$utime = time();
$filename = array(  
'randomizer' => $rfile,
'time' => $utime
);  
$filename = json_encode($filename);  
$name = md5($filename);
$yuklenecek_dosya = getcwd()."/temp/". $name.".f";
$boyut = $_FILES['dosya']["size"];


if (move_uploaded_file($_FILES['dosya']['tmp_name'], $yuklenecek_dosya))
{
	$callback = [];
	$callback["name"] = $name;
	$callback["size"] = $boyut;
	echo json_encode($callback);
} else {
    echo "0";
}
exit();
}
}
?>
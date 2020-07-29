<?php 
include("globaldefine.php");
//login işlemleri
if (isset($_POST['login_form']) and isset($_POST['username']) and isset($_POST['password']) and isset($_SESSION['sec_code']) and isset($_POST['token']) ){
	$sess_code = $_SESSION['sec_code'];
	$post_code = $_POST['token'];
	if($sess_code != $post_code)
		exit();

$kadi = $_POST['username'];
$sifre = $_POST['password'];
$var_mi = $vt->veri("SELECT COUNT(id) FROM users WHERE username='".$kadi."' and password='".$sifre."'");
$callback = [];
if($var_mi) {
$_SESSION['giris'] = $kadi;
$isadmin = $vt->veri("SELECT role FROM users WHERE username='".$kadi."' and password='".$sifre."'");
if($isadmin == 1){
	$callback["isadmin"] = 1;
} else {
	$callback["isadmin"] = 0;
}
$callback["code"] = 1;
} else {
$callback["code"] = 0;
}
echo json_encode($callback);
exit();
}
// logout
if (isset($_POST['logout'])){
if($_POST["logout"]=="1"){
if (isset($_SESSION['giris'])){
    @session_destroy ();
    echo "1";
} else {
    echo "0";

}
exit();
}
}
//register işlemleri
if (isset($_POST['register_form']) and isset($_POST['name']) and isset($_POST['surname']) and isset($_POST['username']) and isset($_POST['email']) and isset($_SESSION['sec_code']) and isset($_POST['token']) ){
$id = uniqid();
$name = $_POST['name'];
$surname = $_POST['surname'];
$username = $_POST['username'];
$email = $_POST['email'];
if($name == "" or $surname=="" or $username == "" or $email == ""){
	echo "2"; // boş için
	exit();
}
$var_mi = $vt->veri("SELECT COUNT(id) FROM users WHERE username='".$username."'");
if($var_mi){
	echo "3"; // kullanıcı adı kullanımda
	exit(); 
}
$date = date("Y-m-d H:i:s");
$role = 0;
$quota = register_quota;
$password = randomPassword();
$mailtemplate = getmailtemplate($name,$username,$password,sizetoread($quota));
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; // Hata ayıklama değişkeni: 1 = hata ve mesaj gösterir, 2 = sadece mesaj gösterir
$mail->SMTPAuth = true; //SMTP doğrulama olmalı ve bu değer değişmemeli
$mail->SMTPSecure = 'tls'; // Normal bağlantı için boş bırakın veya tls yazın, güvenli bağlantı kullanmak için ssl yazın
$mail->Host = "smtp.gmail.com"; // Mail sunucusunun adresi (IP de olabilir)
$mail->Port = 587; // Normal bağlantı için 587, güvenli bağlantı için 465 yazın
$mail->IsHTML(true);
$mail->SetLanguage("tr", "phpmailer/language");
$mail->CharSet  ="utf-8";
$mail->Username = "dpp.info.mail.server@gmail.com"; // Gönderici adresiniz (e-posta adresiniz)
$mail->Password = "MLe0VNp3"; // Mail adresimizin sifresi
$mail->SetFrom("dppadmin@dosyapaylasim.com", "DPP Security"); // Mail atıldığında gorulecek isim ve email
$mail->AddAddress($email); // Mailin gönderileceği alıcı adres
$mail->Subject = 'DPP Giris Bilgileri' ; // Email konu başlığı
$mail->Body = $mailtemplate; // Mailin içeriği
if(!$mail->Send()){
  echo "0";
} else {
$vt->ekle('users', array('uniqueid' => $id , 'username' => $username, 'password' => $password, 'name' => $name, 'surname' => $surname, 'email' => $email,'role' => $role,'quota' => $quota,'date' => $date,"password_reset" => "0"));
  echo "1";
}

}
// şifre değiştir
if (isset($_POST['password_change']) and isset($_SESSION['giris']) and isset($_POST['old_password']) and isset($_POST['new_password']) and isset($_POST['again_password'])){
		$sifre_old = $_POST['old_password'];
		$sifre_new = $_POST['new_password'];
		$sifre_again = $_POST['again_password'];
		if($sifre_new != $sifre_again){
			echo "2";
			exit();
		}
		$username = $_SESSION['giris'];
		$var_mi = $vt->veri("SELECT COUNT(id) FROM users WHERE username='".$username."' and password='".$sifre_old."'");
		if($var_mi){
			$vt->duzenle('users', array('password' => $sifre_new), array('username' => $username));

			echo "1";
		} else {
			echo "0";
		}
		exit();
}
// şifremi unuttum işlemleri
if(isset($_POST["password_progress"])){
	// şifre değiştir isteği gönder
	if(isset($_POST["password_reset_request"]) and isset($_POST["username"])){
$username = $_POST['username'];
$callback = [];
$user_control = $vt->veri('SELECT COUNT(id) FROM users WHERE username = "'.$username.'"');
if($user_control < 1){
$callback["code"] = 2;
echo json_encode($callback);
exit();
}
$otp = randomPassword();
$reset_link = str_replace("globalsite.php", "password_reset.php", $_SERVER["PHP_SELF"]);
$reset_link = "http://".$_SERVER["HTTP_HOST"].$reset_link."?username=".$username."&token=".$otp;
$emailhtml = get_reset_template($username,$reset_link);
$user_mail = $vt->veri('SELECT email FROM users WHERE username = "'.$username.'"');
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; // Hata ayıklama değişkeni: 1 = hata ve mesaj gösterir, 2 = sadece mesaj gösterir
$mail->SMTPAuth = true; //SMTP doğrulama olmalı ve bu değer değişmemeli
$mail->SMTPSecure = 'tls'; // Normal bağlantı için boş bırakın veya tls yazın, güvenli bağlantı kullanmak için ssl yazın
$mail->Host = "smtp.gmail.com"; // Mail sunucusunun adresi (IP de olabilir)
$mail->Port = 587; // Normal bağlantı için 587, güvenli bağlantı için 465 yazın
$mail->IsHTML(true);
$mail->SetLanguage("tr", "phpmailer/language");
$mail->CharSet  ="utf-8";
$mail->Username = "dpp.info.mail.server@gmail.com"; // Gönderici adresiniz (e-posta adresiniz)
$mail->Password = "MLe0VNp3"; // Mail adresimizin sifresi
$mail->SetFrom("dppadmin@dosyapaylasim.com", "DPP Security"); // Mail atıldığında gorulecek isim ve email
$mail->AddAddress($user_mail); // Mailin gönderileceği alıcı adres
$mail->Subject = 'DPP Giris Bilgileri' ; // Email konu başlığı
$mail->Body = $emailhtml; // Mailin içeriği
if(!$mail->Send()){
$callback["code"] = 0;
} else {
$vt->duzenle('users', array('password_reset' => $otp), array('username' => $username ));
$callback["code"] = 1;
}
echo json_encode($callback);
exit();
	}
		if(isset($_POST["password_reset"]) and isset($_POST["username"]) and isset($_POST["token"]) and isset($_POST["password_new"]) and isset($_POST["password_new_again"])){
	    $username = $_POST["username"];
	    $token = $_POST["token"];
	    $new = $_POST["password_new"];
	    $new_again = $_POST["password_new_again"];
	    if($new != $new_again){
	        $callback["code"] = 2;
            echo json_encode($callback);
            exit();
	    }
	    $token_control = $vt->veri('SELECT password_reset FROM users WHERE username = "'.$username.'"');
	    if($token_control == "0"){
	    $callback["code"] = 0;
        echo json_encode($callback);
        exit();
	    }
	    
	    $token_control_2 = $vt->veri('SELECT COUNT(id) FROM users WHERE username = "'.$username.'" and password_reset= "'.$token.'"');
        if($token_control_2 < 1){
        $callback["code"] = 0;
        echo json_encode($callback);
        exit();
        }
        $vt->duzenle('users', array('password' => $new), array('username' => $username ));
        $vt->duzenle('users', array('password_reset' => "0"), array('username' => $username ));
        $callback["code"] = 1;
        echo json_encode($callback);
        exit();
	}
}
// Kategori işlemleri
if (isset($_POST['category_progress'])){
	$isadmin = false;
if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$isadmin = ($vt->veri("SELECT role FROM users WHERE username='".$user."'") == 1) ? true : false;
}
$callback = [];
// Yenile
if(isset($_POST['category_refresh']) and isset($_POST["data"])){
$cat = $_POST["data"];
if($cat == "home"){
	$cat = "0";
	$callback["mode"] = 0; 
} else {
	$callback["mode"] = 1; 
}
if ($cat != 0){
$cat_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$cat.'"');
if($cat_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}}

	try {
$categories = $vt->tablo('SELECT * FROM categories WHERE subcategory = "'.$cat.'"');
foreach($categories as $category) {
$count = $bytes = 0;
FileFinder::find(files_root."/".($cat == "0" ? "" : $cat."/").$category->uniqueid, function($file) use (&$count, &$bytes) {
    // the closure updates count and bytes so far
    ++$count;
    $bytes += filesize($file);
}, 1);
	$callback["data"][] = [
        "uniqueid" =>$category->uniqueid,
        "name" =>$category->name,
        "description" =>$category->description,
        "count" =>$count,
        "created_date" =>$category->created_date
    ];
}} catch (Exception $e) {
	$callback["code"] = 0;
echo json_encode($callback);
exit();	
	}
$callback["code"] = 1;
echo json_encode($callback);
exit();
}
// admin kontrol
if(!$isadmin){$callback["code"] = 0;echo json_encode($callback);exit();}
// kategori ekle
if(isset($_POST['category_add']) and isset($_SESSION['giris'])){
$id = uniqid();
if(!isset($_POST['name']) or !isset($_POST['description'])){
	$callback["code"] = 0;
	echo json_encode($callback);
	exit();
}
$name = $_POST['name'];
$des = $_POST['description'];
$date = date("Y-m-d H:i:s");
$sub = null;
if(isset($_POST['subcategory']) and $_POST['subcategory'] != ""){
$sub = $_POST['subcategory'];
}
if($sub != null){
$sub_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$sub.'"');
if($sub_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}}

$vt->ekle('categories', array('uniqueid' => $id , 'name' => $name, 'description' => $des, 'created_date' => $date, 'created_by' => $user, 'subcategory' => ($sub != null ? $sub : "0" )));
$makedr = $id;
if($sub != null){
$makedr = $sub."/".$id;	
}

mkdir(files_root."/".$makedr); 
$callback["code"] = 1;
	echo json_encode($callback);
	exit();
}
// kategori düzenle
if(isset($_POST['category_edit']) and isset($_SESSION['giris']) and isset($_POST['category_edit_id']) and isset($_POST['name']) and isset($_POST['description'])){
$category_edit_id = $_POST['category_edit_id'];
$description = $_POST['description'];
$name = $_POST['name'];
$cat_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$category_edit_id.'"');
if($cat_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}


	$vt->duzenle('categories', array('name' => $name,'description' => $description), array('uniqueid' => $category_edit_id ));
	$callback["code"] = 1;
	echo json_encode($callback);
	exit();
}
// kategori sil
if(isset($_POST['category_delete']) and isset($_POST["id"])){
$id = $_POST["id"];
$cat_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$id.'"');
if($cat_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$sub = null;
$is_parent_exist = $vt->veri('SELECT subcategory FROM categories WHERE uniqueid = "'.$id.'"');
if($is_parent_exist != "0"){
	$sub = $is_parent_exist;
}
/* 
if(isset($_POST['subcategory']) and $_POST['subcategory'] != "0"){
$sub = $_POST['subcategory'];
}*/

$deldr = $id;
$vt->sil('categories', array('uniqueid' => $deldr));
if($sub != null and $sub != "home"){
$deldr = $sub."/".$id;
$vt->sil('files', array('category_id' => $id));	
} else {
	$all_sub_categories = $vt->tablo('SELECT * FROM categories WHERE subcategory = "'.$id.'"');
foreach($all_sub_categories as $sub_categori) {
	$vt->sil('files', array('category_id' => $sub_categori->uniqueid));	
}

$vt->sil('categories', array('subcategory' => $deldr));	
}
deleteDir(files_root."/".$deldr);
$callback["code"] = 1;
echo json_encode($callback);
exit();
}

}
// dosya işlemleri
if (isset($_POST['file_progress'])){
	$isadmin = false;
	$user = "";
	if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$isadmin = ($vt->veri("SELECT role FROM users WHERE username='".$user."'") == 1) ? true : false;
}
	$callback = [];
	// dosyalar listesini yenile
if(isset($_POST['file_refresh'])){
if(!isset($_POST['uniqueid'])){
	$callback["code"] = 0;
	echo json_encode($callback);
	exit();
}
$uniqueid = $_POST['uniqueid'];
$cat_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$uniqueid.'"');
if($cat_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}

$q = 'category_id = "'.$uniqueid.'"';
if(isset($_POST['foruser'])) {
	if(isset($_SESSION['giris'])){
    $q = 'upload_by = "'.$user.'"';
	} else {
	$callback["code"] = 0;
	echo json_encode($callback);
	exit();
	}
}
try {
$files = $vt->tablo('SELECT * FROM files WHERE '.$q);
$i = 0;
foreach($files as $file) {
	$callback["data"][] = [
        "file_name" =>$file->file_name,
        "file_description" =>$file->file_description,
        "upload_by" =>$file->upload_by,
        "file_size_byte" =>$file->file_size,
        "file_size" =>sizetoread($file->file_size),
        "download_count" =>$file->download_count,
        "upload_date" =>$file->upload_date,
        "upload_timestamp" =>strtotime($file->upload_date),
		"publicid" =>$file->publicid
    ];
     if (isset($_POST['foruser'])) {
     	$cat_name = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$file->category_id."'");
    	$callback["data"][$i]["category"] = $cat_name;
    }
    $i++;
}	
} catch (Exception $e) {
	$callback["code"] = 0;
	echo json_encode($callback);
	exit();
}
	$callback["code"] = 1;
	echo json_encode($callback);
	exit();
}
// dosya ekle
if(isset($_POST['file_add']) and isset($_SESSION['giris'])){
	if(!isset($_POST['file_name']) or !isset($_POST['file_size']) or !isset($_POST['file_category']) or !isset($_POST['file_tempname'])) {
		$callback["code"] = 0;
		echo json_encode($callback);
		exit();
	}
$id = uniqid();
$name = $_POST['file_name'];
$file_description = "";
try {
	$file_description = $_POST['file_description'];
} catch (Exception $e) { }
$size = intval($_POST['file_size']);
if(!is_int($size)){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$date = date("Y-m-d H:i:s");
$sub = $_POST['file_category'];
$tmpname = $_POST['file_tempname'];
$ip = getRealIpAddr(); // localhostta çalışmıyor

$cat_control = $vt->veri('SELECT COUNT(id) FROM categories WHERE uniqueid = "'.$sub.'"');
if($cat_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
if (!file_exists(getcwd().'/temp/'.$tmpname.".f")) {
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
if($size != filesize(getcwd().'/temp/'.$tmpname.".f")){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
try {
$isroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$sub."'");
if($isroot == "0"){
rename(getcwd().'/temp/'.$tmpname.".f", files_root."/".$sub."/".$id.".f");
} else {
rename(getcwd().'/temp/'.$tmpname.".f", files_root."/".$isroot."/".$sub."/".$id.".f");
}
$vt->ekle('files', array('uniqueid' => $id ,'file_name' => $name , 'file_description' => $file_description, 'file_size' => $size, 'upload_date' => $date, 'upload_by' => $user,'uploader_ip' => $ip, 'download_lastdate' =>"", 'publicid' =>md5($id) ,'category_id' =>$sub ));
} catch (Exception $e) {
$callback["code"] = 0;
echo json_encode($callback);
exit();	
}

//unlink(getcwd().'/temp/'.$name.".f");
$callback["code"] = 1;
echo json_encode($callback);
exit();
}
// dosya ekleme iptal
if(isset($_SESSION['giris']) and isset($_POST['cancel']) and isset($_POST['file_tempname'])){
$tmpname = $_POST['file_tempname'];
if (!file_exists(getcwd().'/temp/'.$tmpname.".f")) {
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
unlink(getcwd().'/temp/'.$tmpname.".f");
}
// dosya şikayet 
if(isset($_POST['file_report']) and isset($_POST['file_report_id']) and isset($_POST['username']) and isset($_POST['file_report_reason']) and isset($_POST['file_report_ex'])){
	$id = uniqid();
	$file_pub = $_POST['file_report_id'];
	$name = $_POST['name'];
	$username = $_POST['username'];
	$reason = $_POST['file_report_reason'];
	$file_report_ex = $_POST['file_report_ex'];
	$date = date("Y-m-d H:i:s");
$file_control = $vt->veri('SELECT COUNT(id) FROM files WHERE publicid = "'.$file_pub.'"');
if($file_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
if($username != 0){
$user_control = $vt->veri('SELECT COUNT(id) FROM files WHERE username = "'.$username.'"');
if($user_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
}



	$vt->ekle('files_reported', array('uniqueid' => $id,'publicid' => $file_pub ,'name' => $name , 'username' => $username, 'reason' => $reason, 'message' => $file_report_ex, 'date' => $date ));
$callback["code"] = 1;
echo json_encode($callback);
exit();
}
// admin kontrol
if(!$isadmin){
if($user == ""){
$callback["code"] = 0;echo json_encode($callback);exit();
}
}
// dosya şikayet oku
if(isset($_POST['file_report_read']) and isset($_POST['file_report_id'])){
$file_report_id = $_POST['file_report_id'];
$report_control = $vt->veri('SELECT COUNT(id) FROM files_reported WHERE uniqueid = "'.$file_report_id.'"');
if($report_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
	$files= $vt->tablo('SELECT * FROM files_reported WHERE uniqueid = "'.$file_report_id.'"');	
	$callback = [];
	foreach ($files as $file){
		$file_name = $vt->veri("SELECT file_name FROM files WHERE publicid='".$file->publicid."'");
		$file->file_name=$file_name;
		array_push($callback ,(array)$file);
	}
        echo json_encode($callback);
}
// şikayet sonuçlandır
if(isset($_POST['file_report_result']) and isset($_POST['file_report_id'])){
	$r_id = $_POST['file_report_id'];
	$res = $_POST['file_report_result'];
	$report_control = $vt->veri('SELECT COUNT(id) FROM files_reported WHERE uniqueid = "'.$r_id.'"');
if($report_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
	if($res == "1"){ // dosya sil
$filepub = $vt->veri("SELECT publicid FROM files_reported WHERE uniqueid='".$r_id."'");
$getid  = $vt->veri("SELECT uniqueid FROM files WHERE publicid='".$filepub."'");
$getcat = $vt->veri("SELECT category_id FROM files WHERE publicid='".$filepub."'");
$isroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$getcat."'");
$vt->sil('files', array('publicid' => $filepub ));	
if($isroot == "0"){
unlink(files_root."/".$getcat."/".$getid.".f");
} else {
unlink(files_root."/".$isroot."/".$getcat."/".$getid.".f");
}
		$callback["code"] = 1;
		echo json_encode($callback);
		exit();
	} else if ($res == "0"){ // şikayet sil
		$vt->sil('files_reported', array('uniqueid' => $r_id));
		$callback["code"] = 2;
		echo json_encode($callback);
		exit();
	}
	$callback["code"] = 0;
	echo json_encode($callback);
	exit();
}
// dosya sil
if(isset($_POST['file_delete']) and isset($_POST['file_public'])){
$filepub = $_POST['file_public'];
$file_control = $vt->veri('SELECT COUNT(id) FROM files WHERE publicid = "'.$filepub.'"');
if($file_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
if(!$isadmin){ // silme isteği admin değilse
$upload_by = $vt->veri("SELECT upload_by FROM files WHERE publicid='".$filepub."'");
if($upload_by != $user){ // kullanıcı ile yükleyeni doğrula
	$callback["code"] = 0;echo json_encode($callback);exit();
}
}
$getid  = $vt->veri("SELECT uniqueid FROM files WHERE publicid='".$filepub."'");
$getcat = $vt->veri("SELECT category_id FROM files WHERE publicid='".$filepub."'");
$isroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$getcat."'");
try {
if($isroot == "0"){
unlink(files_root."/".$getcat."/".$getid.".f");
} else {
unlink(files_root."/".$isroot."/".$getcat."/".$getid.".f");
}
$vt->sil('files', array('publicid' => $filepub ));	
} catch (Exception $e) {
$callback["code"] = 0;
echo json_encode($callback);
exit();
}

//unlink(getcwd().'/temp/'.$name.".f");
$callback["code"] = 1;
echo json_encode($callback);
exit();
}
}
// kullanıcı işlemleri
if(isset($_POST['account_progress']) and isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$isadmin = ($vt->veri("SELECT role FROM users WHERE username='".$user."'") == 1) ? true : false;
if(!$isadmin)
	exit();

$callback = [];
// kullanıcıları listele admin veya normal kullanıcı
if(isset($_POST['account_list']) and isset($_POST['list_for'])){
$listfor = $_POST['list_for'];
$role = null;
switch ($listfor) {
case "user":
    $role = 0;
    break;
case "admin":
    $role = 1;
    break;
default:
$callback["code"] = 0;
echo json_encode($callback);
    exit();
}
try {
$users = $vt->tablo('SELECT * FROM users WHERE role="'.$role.'" and username != "admin"');
foreach($users as $user) {
	$callback["data"][] = [
        "uniqueid" =>$user->uniqueid,
        "username" =>$user->username

    ];
}
} catch (Exception $e) {
$callback["code"] = 0;
echo json_encode($callback);
exit();
}

$callback["code"] = 1;
echo json_encode($callback);
exit();
}
// kullanıcının kotasını oku
if(isset($_POST['account_quota_read']) and isset($_POST['account_id'])){
$a_id = $_POST['account_id'];
$user_control = $vt->veri('SELECT COUNT(id) FROM users WHERE uniqueid = "'.$a_id.'"');
if($user_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$callback["quota"] = $vt->veri('SELECT quota FROM users WHERE uniqueid = "'.$a_id.'"');
$callback["code"] = 1;
echo json_encode($callback);
exit();
}
// kullanıcı düzenleme güncelleme adminyap-adminsil-kotadüzenle
if(isset($_POST['account_update_mode']) and isset($_POST['account_id'])){
$mode = $_POST['account_update_mode'];
$a_id = $_POST['account_id'];
switch ($mode) {
case 0:
$user_control = $vt->veri('SELECT COUNT(id) FROM users WHERE uniqueid = "'.$a_id.'"');
if($user_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$vt->duzenle('users', array('role' => '1',"quota" => "0"), array('uniqueid' => $a_id));
    break;
case 1:
$user_control = $vt->veri('SELECT COUNT(id) FROM users WHERE uniqueid = "'.$a_id.'"');
if($user_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$vt->duzenle('users', array('role' => '0',"quota" => register_quota), array('uniqueid' => $a_id));
    break;
case 2:
$user_control = $vt->veri('SELECT COUNT(id) FROM users WHERE uniqueid = "'.$a_id.'"');
if($user_control < 1){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$q_edit = intval($_POST['quota_edit']);
if(!isset($_POST['quota_edit']) or !is_int($q_edit)){
$callback["code"] = 0;
echo json_encode($callback);
exit();
}
$vt->duzenle('users', array('quota' => $q_edit), array('uniqueid' => $a_id));
break;
default:
$callback["code"] = 0;
echo json_encode($callback);
    exit();
}	
$callback["code"] = 1;
echo json_encode($callback);
exit();
	}

}


function get_reset_template($username,$link){
	$message = '<!doctype html><html> <head> <meta name="viewport" content="width=device-width"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <title>Şifre Değiştirme İsteği</title> <style>@media only screen and (max-width: 620px){table[class=body] h1{font-size: 28px !important; margin-bottom: 10px !important;}table[class=body] p, table[class=body] ul, table[class=body] ol, table[class=body] td, table[class=body] span, table[class=body] a{font-size: 16px !important;}table[class=body] .wrapper, table[class=body] .article{padding: 10px !important;}table[class=body] .content{padding: 0 !important;}table[class=body] .container{padding: 0 !important; width: 100% !important;}table[class=body] .main{border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important;}table[class=body] .btn table{width: 100% !important;}table[class=body] .btn a{width: 100% !important;}table[class=body] .img-responsive{height: auto !important; max-width: 100% !important; width: auto !important;}}@media all{.ExternalClass{width: 100%;}.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height: 100%;}.apple-link a{color: inherit !important; font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important; line-height: inherit !important; text-decoration: none !important;}#MessageViewBody a{color: inherit; text-decoration: none; font-size: inherit; font-family: inherit; font-weight: inherit; line-height: inherit;}.btn-primary table td:hover{background-color: #34495e !important;}.btn-primary a:hover{background-color: #34495e !important; border-color: #34495e !important;}}</style> </head> <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;"> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td><td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;"> <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;"> <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">TNKU Dosya Paylaşım Platformu Şifre Değiştirme İsteği</span> <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;"> <tr> <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;"> <h3> TNKU Dosya Paylaşım Platformu </h3> <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Merhaba '.$username.',</p><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Şifre değiştirme isteğin üzerine bu mail gönderildi. Şifreni değiştirmek istiyorsan aşağıdaki bağlantıdan şifre değiştirme işlemine devam edebilirsin.</p><table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;"> <tbody> <tr> <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;"> <tbody> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;"> <a href="'.$link.'" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">Şifremi Değiştir</a> </td></tr></tbody> </table> </td></tr></tbody> </table> </td></tr></table> </td></tr></table> <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"> <tr> <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;"> <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">TNKU Dosya Paylaşım Platformu</span> </tr><tr> <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;"> Powered by <a href="http://www.abdullahemreturkoglu.com" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Abdullah Emre Türkoğlu</a>. </td></tr></table> </div></div></td><td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td></tr></table> </body></html>';
	return $message;
}
function getmailtemplate($name,$username,$password,$quota){
    $message = '<!doctype html><html> <head> <meta name="viewport" content="width=device-width"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <title>Giriş Bilgileriniz</title> <style>@media only screen and (max-width: 620px){table[class=body] h1{font-size: 28px !important; margin-bottom: 10px !important;}table[class=body] p, table[class=body] ul, table[class=body] ol, table[class=body] td, table[class=body] span, table[class=body] a{font-size: 16px !important;}table[class=body] .wrapper, table[class=body] .article{padding: 10px !important;}table[class=body] .content{padding: 0 !important;}table[class=body] .container{padding: 0 !important; width: 100% !important;}table[class=body] .main{border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important;}table[class=body] .btn table{width: 100% !important;}table[class=body] .btn a{width: 100% !important;}table[class=body] .img-responsive{height: auto !important; max-width: 100% !important; width: auto !important;}}@media all{.ExternalClass{width: 100%;}.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height: 100%;}.apple-link a{color: inherit !important; font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important; line-height: inherit !important; text-decoration: none !important;}#MessageViewBody a{color: inherit; text-decoration: none; font-size: inherit; font-family: inherit; font-weight: inherit; line-height: inherit;}}</style> </head> <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"> <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;"> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td><td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;"> <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;"> <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">TNKU Dosya Paylaşım Platformu Giriş Bilgileriniz</span> <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;"> <tr> <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;"> <h3> TNKU Dosya Paylaşım Platformu </h3> <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Merhaba '.$name.',</p><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Üye olduğun için teşekkürler, giriş bilgilerin aşağıda;</p><table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;"> <tbody> <tr> <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;"> <tbody> <tr> <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: white; border-radius: 5px;border: 1px solid;padding: 5px;"> <p>Kullancı Adı: <b>'.$username.'</b></p><p>Şifre: <b>'.$password.'</b></p></td></tr></tbody> </table> </td></tr></tbody> </table> <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 5px;">Normal üyeler için kota:<b>'.$quota.'</b></p><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Yetkileriniz: Dosya Yükleme, Yüklediğini silme-düzenleme</p></td></tr></table> </td></tr></table> <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"> <tr> <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;"> <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">TNKU Dosya Paylaşım Platformu</span> </td></tr><tr> <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;"> Powered by <a href="http://www.abdullahemreturkoglu.com" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Abdullah Emre Türkoğlu</a>. </td></tr></table> </div></div></td><td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td></tr></table> </body></html>';
return $message;
}

 ?>
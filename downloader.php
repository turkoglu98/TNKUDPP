<?php
include("./globaldefine.php");

@ini_set('error_reporting', E_ALL & ~ E_NOTICE);

if(isset($_GET["file"])){

    $filepub =$_GET["file"]; 
    if(!isValidMd5($filepub)){
        echo "Yanlış Bilgi";
        exit();
    }
    $var_mi = $vt->veri("SELECT COUNT(id) FROM files WHERE publicid='".$filepub."'");
    $fileorj = "";
   if($var_mi == 0){
       echo "Dosya Bulunamadı";
       exit();
    }
    $down_count = intval($vt->veri("SELECT download_count FROM files WHERE publicid='".$filepub."'"));

    $getname = $vt->veri("SELECT file_name FROM files WHERE publicid='".$filepub."'");
    $getsize = $vt->veri("SELECT file_size FROM files WHERE publicid='".$filepub."'");
    $getfilefrom = $vt->veri("SELECT upload_by FROM files WHERE publicid='".$filepub."'");
    $getupload_date = $vt->veri("SELECT upload_date FROM files WHERE publicid='".$filepub."'");
    if(isset($_GET["dl"])){
    $getid = $vt->veri("SELECT uniqueid FROM files WHERE publicid='".$filepub."'");
    $fileorj = $getid;
    $getname = $vt->veri("SELECT file_name FROM files WHERE publicid='".$filepub."'");
    $getcat = $vt->veri("SELECT category_id FROM files WHERE publicid='".$filepub."'");
    $file_path = files_root."/";
    $isroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$getcat."'");
	if($isroot == "0"){
	$file_path = files_root."/".$sub."/".$getid .".f";
	} else {
	$file_path = files_root."/".$isroot."/".$getcat."/".$getid.".f";
	}
	
$path_parts = pathinfo($getname);
$file_name  = $path_parts['basename'];
$file_ext   = $path_parts['extension'];


// allow a file to be streamed instead of sent as an attachment
$is_attachment = true;
// make sure the file exists
if (is_file($file_path))
{
	$file_size  = filesize($file_path);
	$file = @fopen($file_path,"rb");
	if ($file)
	{
		// set the headers, prevent caching
		header("Pragma: public");
		header("Expires: -1");
		header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
		header('Content-Disposition: attachment; filename="'.$getname.'"');

        // set appropriate headers for attachment or streamed file
        if ($is_attachment) {
                header('Content-Disposition: attachment; filename="'.$getname.'"');
        }
        else {
                header('Content-Disposition: inline;');
                header('Content-Transfer-Encoding: binary');
        }

        // set the mime type based on extension, add yours if needed.
        $ctype_default = "application/octet-stream";
        $content_types = array(
                "exe" => "application/octet-stream",
                "zip" => "application/zip",
                "mp3" => "audio/mpeg",
                "mpg" => "video/mpeg",
                "avi" => "video/x-msvideo",
        );
        $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
        header("Content-Type: " . $ctype);

		//check if http_range is sent by browser (or download manager)
		if(isset($_SERVER['HTTP_RANGE']))
		{
			list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if ($size_unit == 'bytes')
			{
				//multiple ranges could be specified at the same time, but for simplicity only serve the first range
				//http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
				list($range, $extra_ranges) = explode(',', $range_orig, 2);
			}
			else
			{
				$range = '';
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				exit;
			}
		}
		else
		{
			$range = '';
		}

		//figure out download piece from range (if set)
		list($seek_start, $seek_end) = explode('-', $range, 2);

		//set start and end based on range (if set), else set defaults
		//also check for invalid ranges.
		$seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)),($file_size - 1));
		$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);
	 
		//Only send partial content header if downloading a piece of the file (IE workaround)
		if ($seek_start > 0 || $seek_end < ($file_size - 1))
		{
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
			header('Content-Length: '.($seek_end - $seek_start + 1));
		}
		else
		  header("Content-Length: $file_size");

		header('Accept-Ranges: bytes');
    
		set_time_limit(0);
		fseek($file, $seek_start);
		
		while(!feof($file)) 
		{
			print(@fread($file, 1024*8));
			ob_flush();
			flush();
			if (connection_status()!=0) 
			{
				@fclose($file);
				exit;
			}			
		}
		
		// file save was a success
	$down_count = $down_count+1;
    $vt->duzenle('files', 'download_count='.$down_count.'', 'publicid="'.$filepub.'"');
    $vt->duzenle('files', array('download_lastdate' =>  date("Y-m-d H:i:s")), array('publicid' => $filepub));
		@fclose($file);
		exit;
	}
	else 
	{
		// file couldn't be opened
		header("HTTP/1.0 500 Internal Server Error");
		exit;
	}
}
else
{
	// file does not exist
	header("HTTP/1.0 404 Not Found");
	exit;
}
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TNKÜ - Dosya Paylaşım Platformu</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="./js/jquery.min.js"></script>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script type="module" src="https://unpkg.com/ionicons@5.1.0/dist/ionicons/ionicons.esm.js"></script>
<style type="text/css">
	body{padding-top:40px;padding-bottom:40px;background-color:#eee}
	    @-moz-keyframes pulse{
  0%{    box-shadow:0 0 2px rgb(48, 46, 46)}
  50%{  box-shadow:0 0 25px rgb(48, 46, 46)}
  100%{  box-shadow:0 0 2px rgb(48, 46, 46)}
}

@-webkit-keyframes pulse{
  0%{    box-shadow:0 0 2px rgb(48, 46, 46)}
  50%{  box-shadow:0 0 25px rgb(48, 46, 46)}
  100%{  box-shadow:0 0 2px rgb(48, 46, 46)}
}
.card {  
-webkit-animation: pulse 2s infinite;-moz-animation: pulse 2s infinite;
	-webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;}
</style>
</head>
<body>
		<div class="container">
  <div class="row">
    <div class="col-sm">
    	<center><h3>TNKÜ - Dosya Paylaşım Platformu</h3>
    		<br>
    		<div class="alert alert-dark" role="alert" style="width: 22em;color: #eeeeee;background-color: #343a40;display: none;" id="down_success">
  İndirme başladı, bu sayfayı kapatabilirsiniz.
</div>
   <div class="card text-white bg-dark mb-3" style="max-width: 22rem;" id="down_info">
  <div class="card-header"><b><?= $getname; ?></b> Dosyasını İndir</div>
  <div class="card-body" style="text-align: left;">
    <p class="card-text">Dosya Adı: <b><?= $getname; ?></b></p>
    <p class="card-text">Dosya Boyutu: <b><?= sizetoread($getsize); ?></b></p>
    <p class="card-text">İndirilme Sayısı: <b><?= $down_count; ?></b></p>
    <center><a id="download" href="./downloader.php?file=<?php echo $filepub; ?>&dl=1" target="_Blank"><button type="button" class="btn btn-secondary" style="margin-bottom: 5px"><ion-icon name="cloud-download-outline" style="font-size: 25px;   vertical-align: middle;"></ion-icon><span style="vertical-align: middle;">Dosyayı İndir</span></button></a></center>
    <div id="file_info" style="position: absolute;bottom: 1px;left: 1px;font-size: 12px;font-style: italic;width: 100%"><span>@<?= $getfilefrom ?></span><span style="position: absolute;right: 1px;bottom: 1px"><?= $getupload_date ?></span></div>
  </div>
</div>
</center>
</div>
</div>
</div>
<footer style="text-align:center;color: rgb(7, 62, 90);font-size: 11px;">Copyright © <?php echo date("Y"); ?> <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a></footer>
<script type="text/javascript">
	$( "#download" ).on( "click", function( event ) {
	$("#down_info").slideUp("slow", function() {
							$("#down_success").fadeIn("slow");
						});
	});
</script>
    	</body>
</html>
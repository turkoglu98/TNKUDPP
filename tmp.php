<?php
include("globaldefine.php");
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
$filepub = "56f06eb9d323278889b2958b76cb2239";
 $down_count = $vt->veri("SELECT download_count FROM files WHERE publicid='".$filepub."'");
    $getname = $vt->veri("SELECT file_name FROM files WHERE publicid='".$filepub."'");
    $getsize = $vt->veri("SELECT file_size FROM files WHERE publicid='".$filepub."'");
    $getfilefrom = $vt->veri("SELECT upload_by FROM files WHERE publicid='".$filepub."'");
    $getupload_date = $vt->veri("SELECT upload_date FROM files WHERE publicid='".$filepub."'");
    $down_count = $down_count+1;
    echo $down_count;

  //  $vt->duzenle('files', array('download_count' => strval($down_count)), array('publicid' => $filepub));
    $vt->duzenle('files', 'download_count='.$down_count.'', 'publicid="'.$filepub.'"');
         exit();
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
          $(document).ready(function(){
  $("#myuploads").click(function(){
    $("#files_table").slideDown();

  });
});
    </script>
</head>
<body>
 <h4 class="clickable" id="myuploads" style="width: 150px;">Yüklemelerim<div class="loader" style="position: absolute;top: 3px;left: 170px; display: none;" id="files_loader"></div></h4>
 <div id="files_table" style="display:none;">
egjjfoısdjf<br>
sdsdfpsopv<br>emfpsdpf<br>sdfsdföpsdp
 </div>
</body>
</html>
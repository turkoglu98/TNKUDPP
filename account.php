<?php 
include("globaldefine.php");
if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
} else {
	echo '<script type="text/javascript">window.location.href="./login.php";</script>'; exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TNKÜ - Dosya Paylaşım Platformu</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<link rel="stylesheet" href="css/jquery.toast.css">
<script src="./js/jquery.min.js"></script>
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.1.0/dist/ionicons/ionicons.esm.js"></script>
<script type="text/javascript" src="js/jquery.toast.js"></script>
	<style type="text/css">
	.loader{border-radius:50%;border-top:5px solid #343a40;border-bottom:5px solid transparent;border-right:5px solid #343a40;width:25px;height:25px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
	body{padding-top:40px;background-color:#eee}
	html, body{ height:100%}
	.page-item{cursor: pointer}
	#password_change input { margin-right: 3px }
	@media (max-width: 800px) {
#password_change input { margin-bottom: 3px }
#password_change button { margin-bottom: 3px }
}
.clickable {cursor: pointer}

</style>
</head>
<body>
	<div class="container" style="height: 95%;">
  <div class="row">
    <div class="col-sm">
    	<center><a href="index.php" style="color: #212529;text-decoration: none;"><h3>TNKÜ - Dosya Paylaşım Platformu</h3></a><a href="account.php" style="color: #212529;text-decoration: none;"><h5>Üye Paneli</h5></a></center>
<div class="container">
  <div class="row">
    <div class="col">
    	<div style="position: relative;">
     <h4>Şifremi Değiştir<div class="loader" style="position: absolute;top: 3px;left: 175px; display: none;" id="reset_loader"></div></h4>
     <a href="index.php" style="color: #212529;text-decoration: none;position: absolute;right: 1px;top: 1px;"><h5><-Geri Dön</h5></a>
     </div>
     <div style="
    background-color: #343a40;
    height: auto;
    border: 2px solid #666666;
    position: relative;
">
     	<form class="form-inline" style="
    margin-top: 4px;
    margin-left: 8px;
    margin-bottom: 4px;
" id="password_change">
    <input type="hidden" name="password_change" value="1">
    <input type="password" class="form-control" name="old_password" placeholder="Eski Şifre" required>
    <input type="password" class="form-control" name="new_password" placeholder="Yeni Şifre" required>
    <input type="password" class="form-control" name="again_password" placeholder="Yeni Şifre Tekrar" required>
  <button type="submit" class="btn btn btn-secondary" id="pass_button">Şifreyi Değiştir</button>
</form>
    </div>
  </div>
</div>
  <div class="row">
    <div class="col">
      <h4>Yüklemelerim<div class="loader" style="position: absolute;top: 3px;left: 170px; display: none;" id="files_loader"></div></h4>
      	<table class="table table-hover table-sm table-dark" id="files_table">
  <thead>
    <tr>
      <th>Dosya İsmi</th>
      <th align="center">Dosya Açıklaması</th>
      <th align="center">Kategorisi</th>
      <th align="center">Boyutu</th>
      <th align="center">İndirme Sayısı</th>
      <th align="center">Yüklenme Tarihi</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
$files = $vt->tablo('SELECT * FROM files WHERE upload_by = "'.$user.'"');
foreach($files as $file) {
	$cat_name = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$file->category_id."'");
	echo '<tr><th style="display:none">'.$file->publicid.'</th><td><span class="category" style="vertical-align:middle;"><a class="clickable"><ion-icon name="document-outline"></ion-icon><span>'.$file->file_name.'</span></a></span></td><td style="vertical-align:middle;">'.$file->file_description.'</td><td style="vertical-align:middle;">'.$cat_name.'</td><td style="vertical-align:middle;">'.sizetoread($file->file_size).'</td><td style="vertical-align:middle;">'.$file->download_count.'</td><td style="vertical-align:middle;">'.$file->upload_date.'<span id="category_control" style="float: right;"><ion-icon name="close-outline" class="file_delete clickable"></ion-icon></span></td></tr>';
}
  	?>
  	  </tbody>
</table>
      <nav aria-label="...">
  <ul class="pagination justify-content-center" id="page_nav">

  </ul>
</nav>
    </div>
  </div>
</div>
    	</div>
</div>
</div>
<footer style="text-align:center;color: rgb(7, 62, 90);font-size: 11px;">Copyright © <?php echo date("Y"); ?> <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a></footer>
<script type="text/javascript">
	var pageing = 10;
function file_refresh(id){
		$("#categories_table").hide();
		$("#files_table").show();
				$("#files_loader").fadeIn();
			  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: "file_progress=1&file_refresh=1&uniqueid="+id+"&foruser=1",
	        complete:function(a,status) {
	            if (status == "error"){
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		$("#files_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {
	            		$("#files_table tbody").html("");
	            		if(result.data != null){
	            		  result.data.forEach(function(item){
        				  $("#files_table tbody").append('<tr><th style="display:none">'+item.publicid+'</th><td><span class="category" style="vertical-align:middle;"><a class="clickable"><ion-icon name="document-outline"></ion-icon><span>'+item.file_name+'</span></a></span></td><td style="vertical-align:middle;">'+item.file_description+'</td><td style="vertical-align:middle;">'+item.category+'</td><td style="vertical-align:middle;">'+item.file_size+'</td><td style="vertical-align:middle;">'+item.download_count+'</td><td style="vertical-align:middle;">'+item.upload_date+'<span id="category_control" style="float: right;"><ion-icon name="close-outline" class="file_delete clickable"></ion-icon></span>'+'</td></tr>');
           				});
              var trcount = $("#files_table tbody tr").length;
              $("#files_table tbody tr:gt(" + (pageing - 1) + ")").hide();
              var pagenum = Math.ceil(trcount / pageing);
              $("#page_nav").html("");
              for (var i = 1; i <= pagenum; i++)
              {
                $("#page_nav").append('<li class="page-item"><a class="page-link">' + i + '</a></li>');
              }
              $("#page_nav li:first").addClass("active");
	            		}
	                } else if (result.code== 0){
	                	$.toast({heading: 'Hata',text: 'Dosyalar listelenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
	                $("#files_loader").fadeOut();
	            });


	}
		$( document ).ready(function() {
	          $("#page_nav").show();
              var trcount = $("#files_table tbody tr").length;
              $("#files_table tbody tr:gt(" + (pageing - 1) + ")").hide();
              var pagenum = Math.ceil(trcount / pageing);
              for (var i = 1; i <= pagenum; i++)
              {
                $("#page_nav").append('<li class="page-item"><a class="page-link">' + i + '</a></li>');
              }
              $("#page_nav li:first").addClass("active");
          });


	$( "#password_change" ).on( "submit", function( event ) {
  event.preventDefault();
  document.getElementById("pass_button").disabled = true;
  $("#reset_loader").show();
    var postdata = $( this ).serialize();
  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: postdata,
	        complete:function(a,status) {
	            if (status == "error"){
					$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                document.getElementById("pass_button").disabled = false;
	                $("#reset_loader").hide();
	                }
	                    
	                    }
	            }).done(function (data) {
	            	 var result = data;
					if (result == "1") {
					$.toast({heading: 'Başarılı',text: 'Şifreniz başarıyla değiştirildi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });                 
	                   	                    
	                    //$('#result').animate({opacity: 1}, 1000);
	                } else if (result== "0"){
	                	$.toast({heading: 'Hata',text: 'Eski şifrenizi yanlış girdiniz',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });

	                } else if (result== "2"){
	                	$.toast({heading: 'Hata',text: 'Yeni girilen şifreler uyuşmuyor',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });

	                }
	                $("#reset_loader").hide();
	                document.getElementById("pass_button").disabled = false;

	            });
});
	$("body").delegate("#files_table tbody .file_delete", "click", function(){
	var select_row = event.target.parentElement.parentElement.parentElement;
	var uniqueid = $(select_row).find( "th" ).html();
  $("#site_loader").fadeIn();
  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: "file_progress=1&file_delete=1&file_public="+uniqueid,
	        complete:function(a,status) {
	            if (status == "error"){
	            	
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		  $("#site_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {
	            		$.toast({heading: 'Başarılı',text: 'Dosya başarıyla silindi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		file_refresh("1");
	                } else if (result.code== 0){
	                	
	                	$.toast({heading: 'Hata',text: 'Dosya silinirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
	                  $("#site_loader").fadeOut();
	            });


});
	$("body").delegate("#files_table tbody .category a", "click", function(){
	var select_row = event.target.parentElement.parentElement.parentElement.parentElement;
	var uniqueid = $(select_row).find( "th" ).html();
	 window.open("./downloader.php?file="+uniqueid);

});
  $("body").delegate("#page_nav li","click", function( event ) {
    var index = $(event.target.parentElement).index() + 1;
    var gt = pageing * index;
    $("#page_nav li").removeClass("active");
    $(event.target.parentElement).addClass("active");
    $("#files_table tbody tr").hide();
    for (i = gt - pageing; i < gt; i++)
    {
        $("#files_table tbody tr:eq(" + i + ")").show();
    }
});
</script>
</body>
</html>
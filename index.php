<?php 
include("globaldefine.php");
$islogin = false;
$isadmin = false;
$quota_used = 0;
if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$isadmin = ($vt->veri("SELECT role FROM users WHERE username='".$user."'") == 1) ? true : false;
$islogin = true;
$quota_used = $vt->veri('SELECT Sum(file_size) FROM files WHERE upload_by="'.$user.'"');
$quota_total = intval ($vt->veri('SELECT quota FROM users WHERE username="'.$user.'"'));
if($quota_used == null or $quota_used == "")
  $quota_used = 0;
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
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.1.0/dist/ionicons/ionicons.esm.js"></script>
<link rel="stylesheet" href="css/jquery.toast.css">
<script type="text/javascript" src="js/jquery.toast.js"></script>
<script type="text/javascript" src="js/stupidtable.min.js"></script>
<style type="text/css">
	body{padding-top:40px;padding-bottom:15px;background-color:#eee}
  hr{margin-top: 0rem; margin-bottom: 0.8rem;}
@media (max-width: 800px) {
#buttons_panel button{ margin-bottom:2px; }
 }

	.clickable {cursor: pointer}
	.nav_arrow{color: white; margin-left: 2px;margin-right: 2px;top: 2px;position: absolute;}
	.badge{ cursor:pointer }
  .page-item{cursor: pointer}
	.category{display: block;margin-bottom: 2px;font-weight: bold;}
  .card-text{line-height: 15px}
	ion-icon {
  color: white;
  font-size: 25px;
  vertical-align:middle;
}
button span{vertical-align:middle;}
#buttons_panel button{margin-right: 2px}
a span{vertical-align:middle;}
	.loader{border-radius:50%;border-top:5px solid #343a40;border-bottom:5px solid transparent;border-right:5px solid #343a40;width:25px;height:25px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
  .page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #343a40;
    border-color: #343a40;
  }
  th[data-sort]{
      cursor:pointer;
    }
    #last_uploads p{font-size: 12px}
    #top_downloads p{font-size: 12px}
    #last_members p{font-size: 12px}
</style>
</head>
<body>
	<div class="container">
  <div class="row">
    <div class="col-sm">
     <center><h3 style="position:relative">TNKÜ - Dosya Paylaşım Platformu<ion-icon name="information-circle" style="color: black;position: absolute;bottom: -2px;font-size: 30px;cursor:pointer;" data-toggle="modal" data-target="#site_info"></ion-icon></h3></center>
<?php if($islogin){ ?>
<div id="controls_panel" style="position: relative;margin-bottom: 8px;">
	<div id="user_info_panel" style="text-align: left; float: left;z-index: 2">
<span>Hoşgeldin: </span><b><label style="line-height: 0px;"><?php echo $user; ?></label></b><span style="
    margin-left: 5px;
    color: red;
    font-weight: bold;
    cursor: pointer;
    position: relative;
"><ion-icon name="log-out-outline" id="logout" role="img" class="md hydrated" aria-label="log out outline" style="
    color: black;
    position: absolute;
" data-toggle="tooltip" data-placement="right" title="Çıkış Yap"></ion-icon></span><br>
<span>Üyelik: </span><span><b><?php echo ($isadmin ? "Yönetici" : "Üye"); ?></b></span><span>  Kota: </span><span><b><?php echo sizetoread($quota_used)."/".($quota_total == 0 ? "Sınırsız" : sizetoread($quota_total)); ?></b></span>
	</div>
	<div id="buttons_panel" style="text-align: right;">

		<?php if($isadmin){ ?>
<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#category_modal_add"><ion-icon name="add-circle"></ion-icon><span>Kategori Ekle</span></button>
<a href="./admin.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><span>Yönetim Paneli</span><ion-icon name="arrow-forward-outline"></ion-icon></button></a>
		<?php } else { ?>
		<a href="./account.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="person"></ion-icon><span>Üyelik İşlemleri</span></button></a>
		<?php } ?>
</div>
</div>
<?php } else { ?>
<div id="controls_panel" style="position: relative;margin-bottom: 8px;">
  <div id="buttons_panel" style="text-align: right;">
    <a href="./login.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="log-in-outline"></ion-icon><span>Giriş Yap</span></button></a>
    <a href="./register.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="person"></ion-icon><span>Üye Ol</span></button></a>
  </div>
</div>
<?php } ?>
<div id="files_panel_all">
	<h4 style="position: relative;">Kategoriler<div class="loader" style="display:none;position: absolute;top: 3px;left: 125px;" id="site_loader" data-toggle="tooltip" data-placement="right" title="Yükleniyor, lütfen bekleyiniz."></div></h4>
	<div id="navigasyon"    style="position: relative;height: 35px;background-color: #343a40;padding-right: 10px;padding-bottom: 3px;padding-left: 10px;margin-bottom: 1px;border: 2px solid #666666;"><input type="hidden" id="nav_now" nav_type="home" value="home"><a mode="false" class="badge badge-light" data="home" ><ion-icon name="home" size="small" style="color: black;"></ion-icon><span>Anasayfa</span></a>
<!-- width: 100px;float: right; sağa item eklemek için-->
  </div>
	<table class="table table-hover table-sm table-dark" id="categories_table">
  <thead>
    <tr>
      <th>İsmi</th>
      <th align="center">Dosya Sayısı</th>
      <th align="center">Oluşturulma Tarihi</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
$categories = $vt->tablo('SELECT * FROM categories WHERE subcategory = 0');
foreach($categories as $category) {
$count = $bytes = 0;
FileFinder::find(files_root."/".$category->uniqueid, function($file) use (&$count, &$bytes) {
    // the closure updates count and bytes so far
    ++$count;
    $bytes += filesize($file);
}, 1);


  echo '<tr><th style="display:none" mode="0">'.$category->uniqueid.'</th><td><span class="category"><a class="clickable">'.$category->name.'</a></span><p style="display:none">|</p>'.$category->description.'</td><td style="vertical-align:middle;">'.$count.'</td><td style="vertical-align:middle;">'.$category->created_date;
if($isadmin == 1){
	echo '<span id="category_control" style="float: right;"><ion-icon name="create-outline" class="cat_edit clickable" style="margin-right: 4px"  data-catid="'.$category->uniqueid.'" data-toggle="modal" data-target="#category_modal_edit"></ion-icon><ion-icon name="close-outline" class="cat_delete clickable"></ion-icon></span>';
}
  echo '</td></tr>';
}
  	?>
  </tbody>
</table>
	<table class="table table-hover table-sm table-dark" style="display:none" id="files_table">
  <thead>
    <tr>
      <th data-sort="string">Dosya İsmi</th>
      <th align="center" class="no-sort">Dosya Açıklaması</th>
      <th align="center" class="no-sort">Yükleyen</th>
      <th align="center" data-sort="int">Boyutu</th>
      <th align="center" data-sort="int">İndirme Sayısı</th>
      <th align="center" data-sort="int">Yüklenme Tarihi</th>
    </tr>
  </thead>
  <tbody>
  	  </tbody>
</table>
      <nav aria-label="...">
  <ul class="pagination justify-content-center" id="page_nav">

  </ul>
</nav>
	</div>
  <div id="info_all">
    <div class="card text-white bg-dark mb-3" style="max-width: 25rem;float: left;margin-right: 10px" id="last_uploads">
  <div class="card-header">En son yüklemeler</div>
  <div class="card-body">
    <?php
    $files = $vt->tablo('SELECT * FROM files ORDER BY upload_date DESC LIMIT 8');
foreach($files as $file) {
  $getroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$file->category_id."'");
  $getchild = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$file->category_id."'");
  $getparent = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$getroot."'");
  echo '<span class="card-text"><span><a href="downloader.php?file='.$file->publicid.'" target="_Blank" style="color:white"><b>'.$file->file_name.'</b></a> <span style="color: #cbcbcb;font-style: italic;font-size: 80%;">('.$file->download_count.' Kez indirildi)</span></span><p>'.$file->upload_by.' - '.$file->upload_date.' - '.$getparent.'/'.$getchild.' </p></span><hr color="white">';
}
  ?>
  
  </div>
</div>
    <div class="card text-white bg-dark mb-3" style="max-width: 25rem;float: left;margin-right: 10px" id="top_downloads">
  <div class="card-header">En çok indirilenler</div>
  <div class="card-body">
    <?php
    $files = $vt->tablo('SELECT * FROM files ORDER BY download_count DESC LIMIT 8');
foreach($files as $file) {
  $getroot = $vt->veri("SELECT subcategory FROM categories WHERE uniqueid='".$file->category_id."'");
  $getchild = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$file->category_id."'");
  $getparent = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$getroot."'");
  echo '<span class="card-text"><span><a href="downloader.php?file='.$file->publicid.'" target="_Blank" style="color:white"><b>'.$file->file_name.'</b></a> <span style="color: #cbcbcb;font-style: italic;font-size: 80%;">('.$file->download_count.' Kez indirildi)</span></span><p>'.$file->upload_by.' - '.$file->upload_date.' - '.$getparent.'/'.$getchild.' </p></span><hr color="white">';
}
  ?>
  
  </div>
</div>
    <div class="card text-white bg-dark mb-3" style="max-width: 17rem;float: left;margin-right: 10px" id="last_members">
  <div class="card-header">Son Üyeler</div>
  <div class="card-body">
    <?php
    $members = $vt->tablo('SELECT * FROM users ORDER BY date DESC LIMIT 8');
foreach($members as $member) {
  echo '<p><b>@'.$member->username.'</b> - '.$member->name.' '.$member->surname.'</p></span><hr color="white">';
}
  ?>
  
  </div>
</div>

  </div>
</div>
</div>
</div>
<footer style="text-align:center;color: rgb(7, 62, 90);font-size: 11px;">Copyright © <?php echo date("Y"); ?> <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a></footer>
<script type="text/javascript">
		function refresh(data){
		$("#files_table").hide();
    $("#page_nav").hide();
    var nav_now = $("#nav_now").val();
    if(nav_now == "home"){
      $("#info_all").fadeIn("fast");
    } else {
      $("#info_all").fadeOut("fast");
    }
		$("#categories_table").show();
		$("#site_loader").fadeIn();
    $("#categories_table tbody").fadeOut(400, function() {
    $("#categories_table tbody").html("");
			  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: "category_progress=1&category_refresh=1&data="+data,
	        complete:function(a,status) {
	            if (status == "error"){
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		$("#site_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {
	            		
	            		if(result.data != null){
                result.data.forEach(function(item){
                  $("#categories_table tbody").append('<tr><th mode="'+result.mode+'" style="display:none">'+item.uniqueid+'</th><td><span class="category"><a class="clickable">'+item.name+'</a></span><p style="display:none">|</p>'+item.description+'</td><td style="vertical-align:middle;">'+item.count+'</td><td style="vertical-align:middle;">'+item.created_date+'<?php if($isadmin == 1) { ?><span id="category_control" style="float: right;"><ion-icon name="create-outline" class="cat_edit clickable" style="margin-right: 4px" data-catid="'+item.uniqueid+'" data-toggle="modal" data-target="#category_modal_edit"></ion-icon><ion-icon name="close-outline" class="cat_delete clickable"></ion-icon></span><?php } ?>'+'</td></tr>');

                  });          		  
                    
	            		}
	                } else if (result.code== 0){
	                	$.toast({heading: 'Hata',text: 'Kategoriler listelenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
	                $("#site_loader").fadeOut();
                  $("#categories_table tbody").fadeIn(400);
	            });
        
          });
	}
  var pageing = 8;
	function file_refresh(id){
		$("#categories_table").hide();
      $("#site_loader").fadeIn();
		$("#files_table").show();
    $("#files_table tbody").fadeOut(400,function() {
     $("#files_table tbody").html("");
			  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: "file_progress=1&file_refresh=1&uniqueid="+id,
	        complete:function(a,status) {
	            if (status == "error"){
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		$("#site_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {          		
	            		if(result.data != null){
	            		  result.data.forEach(function(item){
        				  $("#files_table tbody").append('<tr><th style="display:none">'+item.publicid+'</th><td><span class="category" style="vertical-align:middle;"><a class="clickable"><ion-icon name="document-outline"></ion-icon><span>'+item.file_name+'</span></a></span></td><td style="vertical-align:middle;">'+item.file_description+'</td><td style="vertical-align:middle;">'+item.upload_by+'</td><td style="vertical-align:middle;" data-sort-value="'+item.file_size_byte+'">'+item.file_size+'</td><td style="vertical-align:middle;">'+item.download_count+'</td><td style="vertical-align:middle;" data-sort-value="'+item.upload_timestamp+'">'+item.upload_date+'<span id="category_control" style="float: right;"><?php if($isadmin == 1) echo '<ion-icon name="close-outline" class="file_delete clickable"></ion-icon>'; ?>'+'<?php if($isadmin != 1){ ?><ion-icon name="alert-circle-outline" class="file_report clickable" data-pubid="'+item.publicid+'" data-toggle="modal" data-target="#file_report"></ion-icon></span><?php } ?></td></tr>');
           				})
               $("#page_nav").html("");
               $("#page_nav").show();
              var trcount = $("#files_table tbody tr").length;
              console.log(trcount);
              $("#files_table tbody tr:gt(" + (pageing - 1) + ")").hide();
              var pagenum = Math.ceil(trcount / pageing);
              console.log("|"+pagenum);
              for (var i = 1; i <= pagenum; i++)
              {
                $("#page_nav").append('<li class="page-item"><a class="page-link">' + i + '</a></li>');
              }
              $("#page_nav li:first").addClass("active");
	            		}
	                } else if (result.code== 0){
	                	$.toast({heading: 'Hata',text: 'Dosyalar listelenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
	                $("#site_loader").fadeOut();
                  $("#files_table tbody").fadeIn(400);
	            });
    
  });

	}
	function navigate(go,name,type=false){	
$('#navigasyon').append('<label style="position: relative;" class="nav_arrow" for="'+go+'">></label><a mode="'+type+'" class="badge badge-light" data="'+go+'" ><ion-icon name="folder" size="small" style="color: black;"></ion-icon><span>'+name+'</span></a>');
$( "#nav_now" ).attr("nav_type",type).change();
$( "#nav_now" ).val(go).change();
	}
$( document ).ready(function() {

  var table_shorter = $('#files_table').stupidtable();
     table_shorter.on("beforetablesort", function (event, data) {
        // data.column - the index of the column sorted after a click
        // data.direction - the sorting direction (either asc or desc)
        $("#msg").text("Sorting index " + data.column)
      });

      table_shorter.on("aftertablesort", function (event, data) {
        var th = $(this).find("th");
        th.find(".arrow").remove();
        var dir = $.fn.stupidtable.dir;

        var arrow = data.direction === dir.ASC ? "&uarr;" : "&darr;";
        th.eq(data.column).append('<span class="arrow">' + arrow +'</span>');
      });

	$("body").delegate("#navigasyon a","click", function( event ) {
	var data = $(this).attr("data");
	var mode = $(this).attr("mode");
	$(this).nextAll("a").remove();
	$(this).nextAll("label").remove();
	$( "#nav_now" ).attr("nav_type",mode).change();
	$( "#nav_now" ).val(data).change();

	if(mode == "false"){
	refresh(data);
    } else{
	file_refresh(data);
    }
	});
  $('[data-toggle="tooltip"]').tooltip();
$("body").delegate("#categories_table tbody .category a", "click", function(){
	var select_row = event.target.parentElement.parentElement.parentElement;
	var uniqueid = $(select_row).find( "th" ).html();
	var mode = $(select_row).find( "th" ).attr("mode");
	if(mode == "0") 
		mode = false;
	else 
		mode = true;

	var name = $(select_row).find( "a" ).html();
	navigate(uniqueid,name,mode);
	if(!mode)
	refresh(uniqueid);
	else
	file_refresh(uniqueid);
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
  $('#file_report').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var pub_id = button.data('pubid');
  var modal = $(this);
  modal.find('#file_report_id').val(pub_id);
  <?php if(!$islogin){ ?>
  modal.find('#f_r_username').val("0");
<?php } else { ?>
  modal.find('#f_r_username').val("<?php echo $user; ?>");
  modal.find('#f_r_name').val("0");
<?php } ?> 
})

  $('#file_report').on('hide.bs.modal', function (e) {
    $("#file_form_report")[0].reset();
  });

  $( "#file_form_report" ).on( "submit", function( event ) {
  event.preventDefault();
  var postdata = $( this ).serialize();
$('#file_report').modal('hide');
  $("#site_loader").fadeIn();
    $.ajax({
      type: 'POST',
      url: 'globalsite.php',
      data: postdata,
          complete:function(a,status) {
              if (status == "error"){
                
                  $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    $("#site_loader").fadeOut();
                  }       
                      }
              }).done(function (data) {
                 var result = JSON.parse(data);
          if (result.code == 1) {
                  $.toast({heading: 'Başarılı',text: 'Dosya şikayet edildi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  } else if (result.code== 0){
                    
                    $.toast({heading: 'Hata',text: 'Dosya şikayet edilirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  } 
                    $("#site_loader").fadeOut();
              });

  });

});

</script>
<div class="modal fade" id="file_report" tabindex="-1" role="dialog" aria-labelledby="file_report" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dosyayı Şikayet Et</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="file_form_report">
        <input type="hidden" name="file_progress" value="1">
        <input type="hidden" name="file_report" value="1">
        <input type="hidden" name="file_report_id" id="file_report_id" value="">
      <div class="modal-body">
<?php if(!$islogin){ ?>
  <input type="hidden" name="username" id="f_r_username" value="0"> 
  <div class="form-group row">
    <label for="f_r_name" class="col-sm-3 col-form-label">İsminiz: </label>
    <div class="col-sm-10">
      <input type="text" name="name" id="f_r_name" class="form-control" autocomplete="off" required> 
    </div>
  </div>
<?php } else { ?>
  <input type="hidden" name="name" id="f_r_name" value="0"> 
  <div class="form-group row">
    <label for="f_r_username" class="col-sm-3 col-form-label">Kullanıcı Adı: </label>
    <div class="col-sm-10">
      <input type="text" name="username" id="f_r_username" class="form-control" autocomplete="off" value="<?php echo $user; ?>" readonly> 
    </div>
  </div>
<?php } ?> 
  <div class="form-group row">
    <div class="col-sm-10">
  <label for="f_r_reason">Şikayet Nedeniniz</label>
    <select class="form-control" name="file_report_reason" id="f_r_reason">
      <option value="Kural dışı yükleme">Kural dışı yükleme</option>
      <option value="Dosya yanlış yerde">Dosya yanlış yerde</option>
      <option value="Telif hakları ihlali/İllegal dosya">Telif hakları ihlali/İllegal dosya</option>
    </select>
 </div>
    </div>
      <div class="form-group row">
 <div class="col-sm-10">
<label for="f_r_reason_ex">Nedeninizi Açıklayın:</label>
    <textarea class="form-control" name="file_report_ex" id="f_r_reason_ex" rows="3" required></textarea>
  </div>
      </div>
  </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-primary">Gönder</button>
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="site_info" tabindex="-1" role="dialog" aria-labelledby="site_info" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title">Site Hakkında</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         </div>
         <div class="modal-body">
            <h5>Tekirdağ Namık Kemal Üniversitesi Dosya Paylaşım Platformu,</h5>
            <p>Herkese açık bir dosya paylaşma servisidir. Ziyaretçiler istediği kategorilerde gezebilir ve istediği dosyayı bilgisayarına indirebilir.</p>
            <p>TNKU DPP sistemine üye olmak ücretsizdir. Üyeler sisteme yükleme yapabilir ve sadece kendi yüklediği dosyaları silebilir. Tüm üyelerin kotası 200MB dır. Daha fazla kota için yöneticilere başvurmanız gerekir.</p>
            <p>Üye olduktan sonra giriş şifresi e-posta adresine gönderilir, üyeler daha sonra sistemden şifrelerini değiştirebilir.</p>
            <p>Yöneticiler sisteme yeni kategori ekleyebilir düzenleyebilir veya silebilir, aynı şekilde yöneticilerin tüm dosyalarda dosyayı kaldırma hakkı bulunmaktadır</p>
            <p>Yöneticiler sistemdeki üyeleri, yönetici rütbesine çıkabilir veya üyelerin kotasını değiştirebilir.</p>
            <p>Sistemde dosya şikayet sistemi bulunmaktadır. Ziyaretçiler veya üyeler yanlış olduğunu düşündükleri dosyaları şikayet edebilirler. Şikayetleri yöneticiler sonuçlandırır.</p>
            <p>Üyelerin şifrelerini unutma durumunda e-posta adreslerine gönderilen sıfırlama linki ile şifrelerini sıfırlayabilirler.</p>
            <h4>Teknik Bilgiler,</h4>
            <p>Bu sitenin tasarımı Responsive şekilde tasarlanmıştır, tüm cihazlara uygun hale getirilmiştir.</p>
            <p>Server-side programlama olarak PHP7.4 Yazılım dili seçilmişir</p>
            <p>Veritabanı olarak MYSQL kullanılmıştır.</p>
            <p>Bu scriptte <b style="font-size: 18px;">güvenlik çalışması yapılmamıştır!</b>, sadece güvenli kod yazılmıştır. Güvenlik çalışması yapılmadan yayınlanmaması gerekir!</p>
            <p>Eklenti olarak Bootstrap v4.5,Jquery 3.5.1, ikonlar için Ionicons, bilgilendirme kutucukları için Jquery Toast Plugin,dosyaları sıralamak için Stupid jQuery Table Sort kullanılmıştır.</p>
             <p> Bu Script <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a> tarafından Tekirdağ Namık kemal Üniversitesine staj projesi için yapılmıştır. Kopyalayabilirsiniz, dağıtabilirsiniz</p>
                 </div>
        </div>
    </div>
</div>
<?php if($islogin){ ?>
  <?php if($isadmin){ ?>
<div id="modals_panel">
  <div class="modal fade" tabindex="-1" id="confirmation" role="dialog">
      <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Onaylayın</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><span id="confirm_for"></span> silmek istediğinizden emin misiniz?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
        <button type="button" class="btn btn-danger" id="confirm_button" onclick="">Sil</button>
      </div>
    </div>
  </div>

</div>
	<div class="modal fade" id="category_modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yeni Kategori Ekle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="category_form_add">
      	<input type="hidden" name="category_progress" value="1">
      	<input type="hidden" name="category_add" value="1">
      	<input type="hidden" name="subcategory" id="subcategory" value="">
      <div class="modal-body">
         <div class="form-group row">
    <label for="k_a_name" class="col-sm-2 col-form-label">İsmi: </label>
    <div class="col-sm-10">
      <input type="text" name="name" class="form-control" id="k_a_name" autocomplete="off" required> 
    </div>
  </div>
    <div class="form-group row">
    <label for="k_a_des" class="col-sm-2 col-form-label">Açıklaması: </label>
    <div class="col-sm-10">
      <input type="text" name="description" class="form-control" id="k_a_des" autocomplete="off" required>
    </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
        <button type="submit" name="category_add" class="btn btn-primary">Ekle</button>
      </div>
      </form>
    </div>
  </div>
</div>
  <div class="modal fade" id="category_modal_edit" tabindex="-1" role="dialog" aria-labelledby="category_modal_edit" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kategoriyi düzenle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="category_form_edit">
        <input type="hidden" name="category_progress" value="1">
        <input type="hidden" name="category_edit" value="1">
        <input type="hidden" name="category_edit_id" id="category_edit_id" value="">
      <div class="modal-body">
         <div class="form-group row">
    <label for="k_a_name" class="col-sm-2 col-form-label">İsmi: </label>
    <div class="col-sm-10">
      <input type="text" name="name" class="form-control" id="k_e_name" autocomplete="off" required> 
    </div>
  </div>
    <div class="form-group row">
    <label for="k_a_des" class="col-sm-2 col-form-label">Açıklaması: </label>
    <div class="col-sm-10">
      <input type="text" name="description" class="form-control" id="k_e_des" autocomplete="off" required>
    </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
        <button type="submit" name="category_edit_button" class="btn btn-primary">Düzenle</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>
<div class="modal fade" id="files_modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Yeni Dosya Yükle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<div class="input-group mb-3">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="file_modal_add_file">
    <label class="custom-file-label" for="file_modal_add_file" id="file_modal_add_displayfile">Dosya Seç</label>
  </div>
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" id="file_modal_add_upload">Yükle</button>
  </div>
  <div class="progress" id="file_upload_progress" style="
    width: 100%;
    margin-top: 3px;
    display: none;
">
  <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
      <form id="file_modal_form_add">
      	<input type="hidden" name="file_progress" value="1">
      	<input type="hidden" name="file_add" value="1">
      	<input type="hidden" name="file_name" id="file_name" value="">
      	<input type="hidden" name="file_category" id="file_category" value="">
      	<input type="hidden" name="file_tempname" id="file_tempname" value="">
      	<input type="hidden" name="file_size" id="file_size" value="">
    <div class="form-group row">
    <label for="f_u_des" class="col-sm-2 col-form-label">Açıklaması: </label>
    <div class="col-sm-10">
      <input type="text" name="file_description" class="form-control" id="f_u_des" autocomplete="off" required>
    </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
        <button type="submit" name="file_modal_button_add" id="file_modal_button_add" class="btn btn-primary" disabled>Ekle</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
$("#file_modal_add_file").change(function() {
  filename = this.files[0].name
  $("#file_modal_add_displayfile").text(filename);
  $("#file_name").val(filename);
});
window.file_cancel = true;
$('#files_modal_add').on('hide.bs.modal', function (e) {
 var tmp = $("#file_tempname").val()
 if(window.file_cancel){
  if(tmp != ""){
  $.ajax({ type: 'POST', url: 'globalsite.php', data: "file_progress=1&cancel=1&file_tempname="+tmp })
  }
}
$("#file_modal_form_add")[0].reset();
$("#file_modal_add_file").val("");
$("#file_tempname").val("");
$("#file_name").val("");
$("#file_modal_add_displayfile").text("Dosya Seç");
document.getElementById("file_modal_add_file").disabled = false;
document.getElementById("file_modal_add_upload").disabled = false;
document.getElementById("file_modal_button_add").disabled = true;
$("#file_upload_progress").hide();
$("#progressBar").css('width',  '0%').attr('aria-valuenow',  "0").text( '0%');

});
$('#files_modal_add').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var cat_id = button.data('catid');
  var modal = $(this);
  modal.find('#file_category').val(cat_id);
  window.file_cancel = true;
})
$( "#file_modal_form_add" ).on( "submit", function( event ) {
  event.preventDefault();
  window.file_cancel = false;
  var postdata = $( this ).serialize();
  var cat_id = $('#file_modal_form_add #file_category').val();
  $("#site_loader").fadeIn();
  $('#files_modal_add').modal('hide');
    $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: postdata,
	        complete:function(a,status) {
	            if (status == "error"){
	            	
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		  $("#site_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {
	            		$.toast({heading: 'Başarılı',text: 'Dosya başarıyla eklendi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		file_refresh(cat_id);
	                } else if (result.code== 0){
	                	
	                	$.toast({heading: 'Hata',text: 'Dosya eklenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
                  
	                  $("#site_loader").fadeOut();
	            });

	});
 <?php if($isadmin){ ?>

$('#category_modal_add').on('hide.bs.modal', function (e) {
$("#category_form_add")[0].reset();
});
$('#category_modal_add').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var cat_id = button.data('getid');
  var modal = $(this);
  if(cat_id != null && cat_id != ""){
  modal.find('.modal-title').text("Yeni Alt Kategori Ekle");
} else {
  modal.find('.modal-title').text("Yeni Kategori Ekle");
	}
  modal.find('#category_form_add #subcategory').val(cat_id);

})

$('#category_modal_edit').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var cat_id = button.data('catid');
  var modal = $(this);
  var info = event.relatedTarget.parentElement.parentElement.parentElement;
  var allinf = $(info).find("td:first").text().split('|');
  if(cat_id != null && cat_id != ""){
      modal.find('#category_form_edit #category_edit_id').val(cat_id);
  modal.find('#category_form_edit #k_e_name').val(allinf[0]);
  } 
  modal.find('#category_form_edit #k_e_des').val(allinf[1]);

})

$( "#category_form_add" ).on( "submit", function( event ) {
  event.preventDefault();
  var postdata = $( this ).serialize();
  var cat_id = $('#category_form_add #subcategory').val();
  $('#category_modal_add').modal('hide');
  $("#site_loader").fadeIn();
  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: postdata,
	        complete:function(a,status) {
	            if (status == "error"){
	            	
	            		$.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		  $("#site_loader").fadeOut();
	                }       
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == 1) {
	            		$.toast({heading: 'Başarılı',text: 'Kategori başarıyla eklendi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	            		if(cat_id != null && cat_id != ""){
	            		refresh(cat_id);
	            	} else {
	            		refresh("home");
	            	}
	                } else if (result.code== 0){
	                	
	                	$.toast({heading: 'Hata',text: 'Kategori eklenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
	                } 
	                  $("#site_loader").fadeOut();
	            });
});
$( "#category_form_edit" ).on( "submit", function( event ) {
  event.preventDefault();
  var postdata = $( this ).serialize();
  var cat_id = $( "#nav_now" ).val();
  $('#category_modal_edit').modal('hide');
  $("#site_loader").fadeIn();
  $.ajax({
      type: 'POST',
      url: 'globalsite.php',
      data: postdata,
          complete:function(a,status) {
              if (status == "error"){
                
                  $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    $("#site_loader").fadeOut();
                  }       
                      }
              }).done(function (data) {
                 var result = JSON.parse(data);
          if (result.code == 1) {
                  $.toast({heading: 'Başarılı',text: 'Kategori başarıyla düzenlendi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  if(cat_id != null && cat_id != ""){
                  refresh(cat_id);
                } else {
                  refresh("home");
                }
                  } else if (result.code== 0){
                    
                    $.toast({heading: 'Hata',text: 'Kategori düzenlenirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  } 
                    $("#site_loader").fadeOut();
              });
});

cat_delete = function(uniqueid,sub){
  $("#confirmation").modal('hide');
  $("#site_loader").fadeIn();
  $.ajax({
      type: 'POST',
      url: 'globalsite.php',
      data: "category_progress=1&category_delete=1&id="+uniqueid+"&subcategory="+sub,
          complete:function(a,status) {
              if (status == "error"){
                
                  $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    $("#site_loader").fadeOut();
                  }       
                      }
              }).done(function (data) {
                 var result = JSON.parse(data);
          if (result.code == 1) {
                  $.toast({heading: 'Başarılı',text: 'Kategori başarıyla silindi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  if(sub != "home"){
                  refresh(sub);
                } else {
                  refresh("home");
                }
                  } else if (result.code== 0){
                    
                    $.toast({heading: 'Hata',text: 'Kategori silinirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  } 
                    $("#site_loader").fadeOut();
              });

}
$("body").delegate("#categories_table tbody .cat_delete", "click", function(){
	var select_row = event.target.parentElement.parentElement.parentElement;
	var uniqueid = $(select_row).find( "th" ).html();
	var type =  $( "#nav_now" ).attr("nav_type");
	var sub = "home";
  var confirm_text = "Kategoriyi";
	if(type == "false"){
	    sub = $( "#nav_now" ).val();
      confirm_text = "Alt kategoriyi";
	}
  $('#confirm_button').attr('onclick', 'cat_delete(\''+uniqueid+'\',\''+sub+'\')');
  $('#confirm_for').html(confirm_text);
  $('#confirmation').modal('show');
});
file_delete = function(uniqueid,cat_id){
  $("#confirmation").modal('hide');
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
                  file_refresh(cat_id);
                  } else if (result.code== 0){
                    
                    $.toast({heading: 'Hata',text: 'Dosya silinirken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                  } 
                    $("#site_loader").fadeOut();
              });
}
$("body").delegate("#files_table tbody .file_delete", "click", function(){
	var select_row = event.target.parentElement.parentElement.parentElement;
	var uniqueid = $(select_row).find( "th" ).html();
	var cat_id = $( "#nav_now" ).val();
  var confirm_text = "Dosyayı";
  $('#confirm_button').attr('onclick', 'file_delete(\''+uniqueid+'\',\''+cat_id+'\')');
  $('#confirm_for').html(confirm_text);
  $('#confirmation').modal('show');

});
<?php } ?>
$( "#nav_now").change(function(){

var nav_now = $(this).val();
var type = $(this).attr("nav_type");
if(nav_now == "home"){
	$("#buttons_panel").html("");
<?php if($isadmin){ ?>
	$("#buttons_panel").append('<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#category_modal_add"><ion-icon name="add-circle"></ion-icon><span>Kategori Ekle</span></button>');
$("#buttons_panel").append('<a href="./admin.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><span>Yönetim Paneli</span><ion-icon name="arrow-forward-outline"></ion-icon></button></a>');
		<?php } else { ?>
		$("#buttons_panel").append('<a href="./account.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="person"></ion-icon><span>Üyelik İşlemleri</span></button></a>');
		<?php } ?>

} else if( nav_now != "home" && type == "false"){
		$("#buttons_panel").html("");
<?php if($isadmin){ ?>
	$("#buttons_panel").append('<button type="button" class="btn btn-dark" data-toggle="modal"  data-getid="'+nav_now+'" data-target="#category_modal_add"><ion-icon name="add-circle"></ion-icon><span>Alt Kategori Ekle</span></button>');
$("#buttons_panel").append('<a href="./admin.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><span>Yönetim Paneli</span><ion-icon name="arrow-forward-outline"></ion-icon></button></a>');
		<?php } else { ?>
		$("#buttons_panel").append('<a href="./account.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="person"></ion-icon><span>Üyelik İşlemleri</span></button></a>');
		<?php } ?>
}
else if( nav_now != "home" && type == "true"){
		$("#buttons_panel").html("");
			$("#buttons_panel").append('<button type="button" class="btn btn-dark" data-catid="'+nav_now+'" data-toggle="modal" data-target="#files_modal_add"><ion-icon name="add-circle"></ion-icon><span>Yükleme Yap</span></button>');
<?php if($isadmin){ ?>
$("#buttons_panel").append('<a href="./admin.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><span>Yönetim Paneli</span><ion-icon name="arrow-forward-outline"></ion-icon></button></a>');
		<?php } else { ?>
		$("#buttons_panel").append('<a href="./account.php" style="color:white;text-decoration: none;"><button type="button" class="btn btn-secondary"><ion-icon name="person"></ion-icon><span>Üyelik İşlemleri</span></button></a>');
		<?php } ?>
}
});

$("body").delegate("#logout","click", function( event ) {
    $.ajax({
        type: "POST",
        url: "globalsite.php",
        data: "logout=1",
        success: function (data) {
          if (data == "1"){
          window.location.href = "./index.php";       
          } else {
              alert("Zaten çıkış yapılmış.");
          window.location.href = "./index.php"; 
          }
      }
    })
  });
function progressHandler(event) {
  var percent = (event.loaded / event.total) * 100;
  //_("progressBar").value = Math.round(percent);
  $("#progressBar").css('width',  Math.round(percent)+'%').attr('aria-valuenow',  Math.round(percent)).text( Math.round(percent)+'%');
}
function completeHandler(event) {
     data = event.target.responseText;
     if(data == "2"){
      $('#files_modal_add').modal('hide');
  $.toast({heading: 'Hata',text: 'Yükleme kotanız dolmuş,<br>dosya silmeyi deneyin.',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
     }
     if (data != 0){
     var result = JSON.parse(data);
     document.getElementById("file_modal_add_file").disabled = true;
     document.getElementById("file_modal_add_upload").disabled = true;
     $("#file_tempname").val(result.name);
     $("#file_size").val(result.size);
     document.getElementById("file_modal_button_add").disabled = false;
     } else {
     alert("Yüklenemedi.");
     }
    
}
function errorHandler(event) {
 alert("Upload Failed");
}
function abortHandler(event) {
  alert("Upload Aborted");
}
$("body").delegate("#file_modal_add_upload","click", function( event ) {
$("#file_upload_progress").show();
$("#progressBar").css('width',  '0%').attr('aria-valuenow',  "0").text( '0%');   
var formdata = new FormData();
var files = $('#file_modal_add_file')[0].files[0]; 
                formdata.append('dosya', files); 
  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "./uploader.php");
  ajax.send(formdata);
  });
});
</script>
<?php } ?>
</body>
</html>
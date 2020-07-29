<?php 
include("globaldefine.php");
require_once 'DiskStatus.class.php';
if(isset($_SESSION['giris'])){
$user = $_SESSION['giris'];
$isadmin = ($vt->veri("SELECT role FROM users WHERE username='".$user."'") == 1) ? true : false;
if(!$isadmin){
   echo '<script type="text/javascript">window.location.href="./index.php";</script>'; exit();
}
} else {
    echo '<script type="text/javascript">window.location.href="./login.php";</script>'; exit();
}
$count_files_reported = $vt->veri('SELECT COUNT(id) FROM files_reported');
$count_files_uploaded = $vt->veri('SELECT COUNT(id) FROM files WHERE upload_by="'.$user.'"');
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
    .loader{border-radius:50%;border-top:5px solid #343a40;border-bottom:5px solid transparent;border-right:5px solid #343a40;width:25px;height:25px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
    body{padding-top:40px;background-color:#eee}
    html, body{ height:100%}
    #password_change input { margin-right: 3px }
    .page-item{cursor: pointer}
    @media (max-width: 800px) {
#password_change input { margin-bottom: 3px }
#password_change button { margin-bottom: 3px }
#all_info div {margin-bottom:5px}
}
.clickable {cursor: pointer}

</style>
</head>
<body>
    <div class="container" style="height: 95%;">
  <div class="row">
    <div class="col-sm">
        <center><a href="index.php" style="color: #212529;text-decoration: none;"><h3>TNKÜ - Dosya Paylaşım Platformu</h3></a><a href="admin.php" style="color: #212529;text-decoration: none;"><h5>Yönetim Paneli</h5></a></center>
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
      <h4 class="clickable" id="control_text" style="width: 150px;">İşlemler</h4>

      <div class="btn-group" role="group">
      <button type="button" class="btn btn btn-dark" data-toggle="modal" data-target="#user_list_modal" data-for="admin_add" style="margin-right: 2px">Admin Ata</button>
      <button type="button" class="btn btn btn-dark" data-toggle="modal" data-target="#user_list_modal" data-for="admin_delete" style="margin-right: 10px">Admin sil</button>
      </div>
      <button type="button" class="btn btn btn-dark" data-toggle="modal" data-target="#user_list_modal" data-for="user_quota">Kota düzenle</button>

<div class="modal fade" id="user_list_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div style="width: 100%">
        <input type="hidden" id="listfor" value="">
<input type="text" class="form-control search-box" id="user_search" style="float: left;margin-bottom: 5px;margin-left: 10px;width: 230px;" searchfor="userlist" placeholder="Kullanıcı Ara"><br><br>

<div class="form-row">
      <form id="user_list_form" class="form-inline" style="width: 100%">
    <input type="hidden" name="account_progress" value="1">
    <input type="hidden" name="account_update_mode" value="">
     <div class="container-fluid">
            <div class="row">
          <div class="col-8 col-sm-6">
                 <select class="custom-select" name="account_id" id="userlist" size="6" style="width: 230px;" required>
        </select>
          </div>
          <div class="col-4 col-sm-6">
                    <div id="user_list_actions">
             </div>
          </div>
        </div>
      </div>
    </form>
  </div>

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
      </div>
    </div>
  <div class="row">
    <div class="col">
      <h4 class="clickable toggle_selector" id="all_info_text" style="width: 150px;" toggle_for="all_info">Bilgiler<span>&darr;</span></h4>
      <div id="all_info" style="display:none;">
                  <div id="siteStatus" style="
    border: 2px solid;
    padding: 5px;
    width: 225px;
    border-radius: 5px;
    float: left;
    margin-right: 5px
">
<center><b>Dosyalar & Kategoriler</b></center>
<?php 
$count_cat = $vt->veri('SELECT COUNT(id) FROM categories WHERE subcategory = "0"');
$count_sub_cat = $vt->veri('SELECT COUNT(id) FROM categories WHERE subcategory != "0"');
$count_files = $vt->veri('SELECT COUNT(id) FROM files');
?>
<span>Toplam Ana Kategoriler: </span><b><?= $count_cat ?></b>
<span>Toplam Alt Kategoriler: </span><b><?= $count_sub_cat ?></b>
<span>Toplam Dosya Sayısı: </span><b><?= $count_files ?></b>
</div>
            <div id="userStatus" style="
    border: 2px solid;
    padding: 5px;
    width: 225px;
    border-radius: 5px;
    float: left;
    margin-right: 5px
">
<center><b>Tüm Üyeler</b></center>
<?php 
$count_admin = $vt->veri('SELECT COUNT(id) FROM users WHERE role = "1"');
$count_users = $vt->veri('SELECT COUNT(id) FROM users WHERE role = "0"');
?>
<span>Yöneticiler: </span><b><?= $count_admin ?></b><br>
<span>Normal Üyeler:  </span><b><?= $count_users ?></b>
</div>
      <div id="diskStatus" style="
    border: 2px solid;
    padding: 5px;
    width: 225px;
    border-radius: 5px;
    text-align: center;float: left;
">
<center><b>Disk Bilgileri</b></center>
      <?php 
$diskStatus = new DiskStatus('/');
$totalSpace = $diskStatus->totalSpace();
$freeSpace = $diskStatus->freeSpace();
$barWidth = ($diskStatus->usedSpace()/100) * 215;

      ?>
        <div class="progress" style="width: 215px;background-color: white;">
  <div class="progress-bar" role="progressbar" style="width: <?php echo $barWidth; ?>px" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><?php echo $diskStatus->usedSpace(); ?>%</div>
</div>

      Boş: <?= $freeSpace ?> (<?= $totalSpace ?>)
    </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <h4 class="clickable toggle_selector" id="myuploads" style="width: 150px;" toggle_for="files_table_div">Yüklemelerim(<?= $count_files_uploaded ?>)<span>&darr;</span><div class="loader" style="position: absolute;top: 3px;left: 170px; display: none;" id="files_loader"></div></h4>
      <div id="files_table_div" style="display:none;">
        <table class="table table-hover table-sm table-dark" id="files_table" >
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
  <div class="row">
    <div class="col">
      <h4 class="clickable toggle_selector" id="reported_files" style="width: 240px;" toggle_for="reported_table_div">Dosya Şikayetleri(<?= $count_files_reported ?>)<span>&darr;</span><div class="loader" style="position: absolute;top: 3px;left: 170px; display: none;" id="reported_loader"></div></h4>
      <div id="reported_table_div" style="display:none;">
        <table class="table table-hover table-sm table-dark" id="reported_table" >
  <thead>
    <tr>
      <th>Dosya İsmi</th>
      <th align="center">Kategorisi</th>
      <th align="center">Şikayet Sebebi</th>
      <th align="center">Şikayet Tarihi</th>
    </tr>
  </thead>
  <tbody>
        <?php 
$files_reported = $vt->tablo('SELECT * FROM files_reported ORDER BY date asc');
foreach($files_reported as $file) {
  $file_name = $vt->veri("SELECT file_name FROM files WHERE publicid='".$file->publicid."'");
  if($file_name == "" or $file_name == null){
    $vt->sil('files_reported', array('publicid' => $file->publicid));  
    continue;
  }
  $cat_id = $vt->veri("SELECT category_id FROM files WHERE publicid='".$file->publicid."'");
  $cat_name = $vt->veri("SELECT name FROM categories WHERE uniqueid='".$cat_id."'");
 echo '<tr><th style="display:none" class="'.$file->uniqueid.'">'.$file->uniqueid.'</th><td style="vertical-align:middle;">'.$file_name.'</td>'.'</th><td style="vertical-align:middle;">'.$cat_name.'</td>'.'<td style="vertical-align:middle;">'.$file->reason.'</td>'.'<td style="vertical-align:middle;"><span style="vertical-align: middle;">'.$file->date.'</span><button type="button" class="btn btn-secondary btn-sm" style="float: right;" data-uid="'.$file->uniqueid.'" data-toggle="modal" data-target="#report_modal_read">Değerlendir</button></td>';
}

  ?>
    </tbody>
  </table>
      <nav aria-label="...">
  <ul class="pagination justify-content-center" id="page_nav_reports">

  </ul>
</div>

</div>
</div>


    </div>
  </div>
</div>
        </div>
</div>
<footer style="text-align:center;color: rgb(7, 62, 90);font-size: 11px;">Copyright © <?php echo date("Y"); ?> <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a></footer>
</div>

  <div class="modal fade" id="report_modal_read" tabindex="-1" role="dialog" aria-labelledby="report_modal_read" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dosya Şikayeti Detayları</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="i_r_f">
        <input type="hidden" name="i_r_3"id="f_r_report_id" value="">
      <div class="modal-body">
         <div class="form-group row">
    <label for="f_r_file_name" class="col-sm-2 col-form-label">Dosya İsmi: </label>
    <div class="col-sm-10">
      <input type="text" name="i_r_1" class="form-control" id="f_r_file_name" autocomplete="off" readonly> 
    </div>
  </div>
    <div class="form-group row">
    <label for="f_r_file_reason" class="col-sm-2 col-form-label">Şikayet Sebebi: </label>
    <div class="col-sm-10">
      <input type="text" name="i_r_2" class="form-control" id="f_r_file_reason" autocomplete="off" readonly>
    </div>
  </div>
      <div class="form-group row">
    <label for="f_r_file_from" class="col-sm-2 col-form-label">Tarafından: </label>
    <div class="col-sm-10">
      <input type="text" name="i_r_4" class="form-control" id="f_r_from" autocomplete="off" readonly>
    </div>
  </div>
        <div class="form-group row">
    <label for="f_r_file_from" class="col-sm-2 col-form-label">Mesajı: </label>
    <div class="col-sm-10">
       <textarea class="form-control" name="i_r_5" id="f_r_message" rows="3" readonly></textarea>
    </div>
  </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick='report_progress("1")'>Dosyayı Kaldır</button>
        <button type="button" class="btn btn-primary" onclick='report_progress("0")'>Şikayeti Kaldır</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
     $('[data-toggle="tooltip"]').tooltip();
    window.all_list=[];
init_searchboxs = function(){
  window.all_list = [];
  $( ".search-box").each(function( index ) {
var sf_id = $(this).attr("searchfor");
all_list[sf_id] = new Array();
$( "#"+sf_id+" option" ).each(function( index ) {
  all_list[sf_id][index] =this;
});

});
}

$(".search-box").keyup(function(){
var s = $(this).val();
var sf_id = $(this).attr("searchfor");
var foundlist=[];
$.each( all_list[sf_id], function(index, item ) {
  var st = $(item).text().toLowerCase();
  if(st.indexOf(s.toLowerCase()) != -1){
foundlist.push(this);
  }
});
$( "#"+sf_id).html("");
$.each( foundlist, function(index, item ) {
$( "#"+sf_id).append(this);
});
});
$(".toggle_selector").click(function(){
var arr_for_id = $(this).attr("toggle_for");
$("#"+arr_for_id ).slideToggle();
var now = $(this).find("span").html(); 
console.log(now);
if (now == "↓")
  $(this).find("span").html("↑"); 
 else
  $(this).find("span").html("↓");

});
$('#user_list_modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
  var modal_for = button.data('for');
  var modal = $(this);
  var list_f = null;
if(modal_for == "admin_add"){
  modal.find('.modal-title').html("Yeni Yönetici Ekle");
  list_f = "user";
  $("#user_list_actions").html("<button type=\"submit\" class=\"btn btn-primary\">Admin yap</button>");
  modal.find('[name ="account_update_mode"]').val("0"); 
}
if(modal_for == "admin_delete"){
modal.find('.modal-title').html("Yönetici Sil");
list_f = "admin";
$("#user_list_actions").html("<button type=\"submit\" class=\"btn btn-primary\">Admin sil</button>");
modal.find('[name ="account_update_mode"]').val("1"); 
}
if(modal_for == "user_quota"){
modal.find('.modal-title').html("Üye Kotası Düzenle");
list_f = "user";
$("#user_list_actions").html("<input type=\"text\" class=\"form-control\" name=\"quota_edit\" onkeyup=\"readable_size()\" style=\"margin-bottom:5px\" placeholder=\"Byte cinsinden kota girin\" required><button type=\"submit\" class=\"btn btn-primary\">Kota Düzenle</button>");
modal.find('[name ="account_update_mode"]').val("2"); 
$('[name ="quota_edit"').tooltip({'trigger':'focus', 'title': ''});
modal.find('[name ="account_id"]').attr("onchange","account_quota_read()");
}
                  $.ajax({
        type: 'POST',
        url: 'globalsite.php',
        data: "account_progress=1&account_list=1&list_for="+list_f,
            complete:function(a,status) {
                if (status == "error"){
                        $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                          $(this).modal("hide");
                    }       
                        }
                }).done(function (data) {
                     var result = JSON.parse(data);
                    if (result.code == 1) {
                        $("#userlist").html("");
                        if(result.data != null){
                          result.data.forEach(function(item){
                            $("#userlist").append("<option value=\""+item.uniqueid+"\">"+item.username+"</option>");
                          });
                          init_searchboxs();
                        }
                      }

                   });

});
    
    
    $('#report_modal_read').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var f_id = button.data('uid');
  var modal = $(this);
  modal.find('#f_r_report_id').val(f_id);
                $.ajax({
        type: 'POST',
        url: 'globalsite.php',
        data: "file_progress=1&file_report_read=1&file_report_id="+f_id,
            complete:function(a,status) {
                if (status == "error"){
                        $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    }       
                        }
                }).done(function (data) {
                     var result = JSON.parse(data);
                     $('#f_r_file_name').val(result[0].file_name);
                     $('#f_r_file_reason').val(result[0].reason);
                     if(result[0].name == "0")
                      $('#f_r_from').val("@"+result[0].username);
                   else {
                      $('#f_r_from').val(result[0].name);
                   }
                    $('#f_r_message').text(result[0].message);
                   });

})
});

  function report_progress(result){
  var f_id = $('#f_r_report_id').val();
                    $.ajax({
        type: 'POST',
        url: 'globalsite.php',
        data: "file_progress=1&file_report_result="+result+"&file_report_id="+f_id,
            complete:function(a,status) {
                if (status == "error"){
                        $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    }       
                        }
                }).done(function (data) {
                     var result = JSON.parse(data);
                     if (result.code == 1) {
                      $.toast({heading: 'Başarılı',text: 'Dosya başarıyla silindi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                      $("#report_modal_read").modal('hide');
                      } else if (result.code== 0){
                        $.toast({heading: 'Hata',text: 'Şikayet sonuçlandırılırken bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    } else if (result.code== 2){
                      $.toast({heading: 'Başarılı',text: 'Şikayet başarıyla silindi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });  
                    } 

                   });

  }
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

              $("#page_nav_reports").show();
              var trcount = $("#reported_table tbody tr").length;
              $("#reported_table tbody tr:gt(" + (pageing - 1) + ")").hide();
              var pagenum = Math.round(trcount / pageing);
              for (var i = 1; i <= pagenum; i++)
              {
                $("#page_nav_reports").append('<li class="page-item"><a class="page-link">' + i + '</a></li>');
              }
              $("#page_nav_reports li:first").addClass("active");

          });

    $( "#password_change" ).on( "submit", function( event ) {
  event.preventDefault();
  document.getElementById("pass_button").disabled = true;
  $("#reset_loader").fadeIn();
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
                    $("#reset_loader").fadeOut();
                    document.getElementById("pass_button").disabled = false;

                });
});
    $( "#user_list_form" ).on( "submit", function( event ) {
  event.preventDefault();
  var postdata = $( this ).serialize();
  $.ajax({
        type: 'POST',
        url: 'globalsite.php',
        data: postdata,
            complete:function(a,status) {
                if (status == "error"){
                  $("#user_list_modal").modal("hide");
                    $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    }
                        
                        }
                }).done(function (data) {
                  var result = JSON.parse(data);
                    if (result.code == 1) {
                  $("#user_list_modal").modal("hide");
                  $.toast({heading: 'Başarılı',text: 'İşlem başarıyla gerçekleştirildi',icon: 'success',position: 'bottom-right',loader: true,loaderBg: '#343a40' });  
                } else if (result.code == 0){
                  $("#user_list_modal").modal("hide");
                    $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                }
                });

});
    $("body").delegate("#files_table tbody .file_delete", "click", function(){
    var select_row = event.target.parentElement.parentElement.parentElement;
    var uniqueid = $(select_row).find( "th" ).html();
    var cat_id = $( "#nav_now" ).val();
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
    $("body").delegate("#page_nav_reports li","click", function( event ) {
    var index = $(event.target.parentElement).index() + 1;
    var gt = pageing * index;
    $("#page_nav_reports li").removeClass("active");
    $(event.target.parentElement).addClass("active");
    $("#reported_table tbody tr").hide();
    for (i = gt - pageing; i < gt; i++)
    {
        $("#reported_table tbody tr:eq(" + i + ")").show();
    }
});
function account_quota_read(){
  var getValue = document.getElementsByName("account_id")[0].value;
    $.ajax({
        type: 'POST',
        url: 'globalsite.php',
        data: "account_progress=1&account_quota_read=1&account_id="+getValue,
            complete:function(a,status) {
                if (status == "error"){
                    
                        $.toast({heading: 'Hata',text: 'Bir hata oluştu',icon: 'error',position: 'bottom-right',loader: true,loaderBg: '#343a40' });
                    }       
                        }
                }).done(function (data) {
                   var result = JSON.parse(data);
                    if (result.code == 1) {
                      $('[name="quota_edit"]').val(result.quota).keyup();
                     }

                });
}
function readable_size(){
  var getValue = document.getElementsByName("quota_edit")[0].value;
  $('[name="quota_edit"]').tooltip('hide')
      .attr('data-original-title', humanFileSize(getValue,true, 2))
      .tooltip('show');
}
function humanFileSize(bytes, si=false, dp=0) {
  const thresh = 1024;

  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }

  const units = si 
    ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] 
    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  let u = -1;
  const r = 10**dp;

  do {
    bytes /= thresh;
    ++u;
  } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


  return bytes.toFixed(dp) + ' ' + units[u];
}
</script>

</body>
</html>
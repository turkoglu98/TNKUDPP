<?php 
include("globaldefine.php");
if(isset($_SESSION['giris'])){
echo '<script type="text/javascript">window.location.href="./index.php";</script>'; exit();
}
$display = false;
if(isset($_GET['username']) and isset($_GET['token'])){
$username = $_GET['username'];
$token = $_GET['token'];
$token_control_2 = $vt->veri('SELECT COUNT(id) FROM users WHERE username = "'.$username.'" and password_reset= "'.$token.'"');
        if($token_control_2 < 1){
        echo "Şifre sıfırlama linki yanlış veya zamanı geçmiş, lütfen yeni bir işlem başlatın.";
        exit();
}
$display = true;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TNKÜ - Dosya Paylaşım Platformu - Giriş Yap</title>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="./js/jquery.min.js"></script>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script src="./js/bootstrap.min.js"></script>
<style>
	body{background-color:#eee}
	html, body{ height:100%}
	.loader{border-radius:50%;border-top:5px solid #007cf9;border-bottom:5px solid transparent;border-right:5px solid #007cf9;width:25px;height:25px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
	</style>
</head>
<body>
	<div class="container" style="height: 95%;">
  <div class="row align-items-center">
    <div class="col-sm">
    </div>
    <div class="col-8">
    	<br>
    	<div id="result" style="visibility: visible; opacity: 0; margin-bottom: 10px;"><span class="alert alert-danger" id="container">.</span></div>
    	<div class="card text-white bg-dark mb-3" id="success_div" style="max-width: 22rem;display: none;">
    	<div class="card-header"><img src="./img/success.png" width="24" height="24" style="margin-right: 5px;"><span style="vertical-align: middle;">İşlem başarılı</span></div>
  <div class="card-body">
     <?php if($display){ ?>
         <p class="card-text">Şifreniz başarıyla değiştirildi.</p>
     <?php } else { ?>
    <p class="card-text">Sıfırlama işleminiz başlatıldı</p>
    <p class="card-text">Sıfırlama linkiniz e-posta adresinize gönderildi.</p>
    <?php } ?>
    <a style="
    position: absolute;
    right: 2px;
    bottom: 2px;
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
" href="./login.php">Giriş Yap-&gt;</a>
  </div>
</div>
      <div id="reset_div" style="position: relative;">
      	<a href="index.php" style="color: #212529;text-decoration: none;"><h3>TNKÜ - Dosya Paylaşım Platformu</h3></a>
<h4 style="margin-left: 40px;margin-bottom: 5px;">Şifremi Unuttum</h4>
      	      	<form class="" id="reset_form" style="margin-top: 25px;">
      		<input type="hidden" name="password_progress" value="1">
      		<?php if($display){ ?>
      		<input type="hidden" name="password_reset" value="1">
      		<input type="hidden" name="token" value="<?= $token ?>">
      		 <?php } else { ?>
      		<input type="hidden" name="password_reset_request" value="1">
      		 <?php } ?>
      		 <div class="form-row">
 <div class="form-group mb-2">
 	<div class="loader" style="display: none;margin-right: 5px;" id="reset_loader"></div>
    <label for="username" class="sr-only">Kullanıcı adı</label>
    <input type="text" name= "username" class="form-control" id="username" value="<?php echo $display ? $username : ""; ?>" placeholder="Kullanıcı adı" <?php echo $display ? "readonly" : ""; ?> required>
  </div>
</div>
  <?php if($display){ ?>
  	<div class="form-row">
  		<div class="form-group mb-2">
  <input type="text" name= "password_new" class="form-control mb-2" placeholder="Yeni şifreniz" required>
  <input type="text" name= "password_new_again" class="form-control mb-2" placeholder="Yeni şifreniz tekrar" required>
</div>
</div>
  <?php }?>

  <button type="submit" class="btn btn-primary mb-2" id="reset_button">Şifremi sıfırla</button>
</form>
      </div>


    	 </div>
    <div class="col-sm">
    </div>
  </div>
</div>
<footer style="text-align:center;color: rgb(7, 62, 90);font-size: 11px;">Copyright © <?php echo date("Y"); ?> <a href="http://abdullahemreturkoglu.com">Abdullah Emre Türkoğlu</a></footer>
<script type="text/javascript">
	$( document ).ready(function() {
$( "#reset_form" ).on( "submit", function( event ) {
  event.preventDefault();
  document.getElementById("reset_button").disabled = true;
  $("#reset_loader").show();
  var postdata = $( this ).serialize();
  $.ajax({
	    type: 'POST',
	    url: 'globalsite.php',
	    data: postdata,
	        complete:function(a,status) {
	            if (status == "error"){
	                $('#container').attr('class', 'alert alert-danger');
	                $("#container").html("Bir Hata Oluştu.");
	                $('#result').animate({ opacity: 1 }, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0 }, 1000); }, 1500);
	                document.getElementById("reset_button").disabled = false;
	                $("#reset_loader").hide();
	                }
	                    
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == "1") {
	            		$("#reset_div").slideUp("slow", function() {
							$("#success_div").fadeIn("slow");
						});
	                } else if (result.code== "0"){
	                    $('#container').attr('class', 'alert alert-danger');
	                    $("#container").html("<?php echo ($display ? "Sıfırlama başarısız,işlemi tekrar başlatın" : "Bir hata oluştu, lütfen tekrar deneyiniz."); ?>");
	                    $('#result').animate({opacity: 1}, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0}, 1000);}, 1500);
	                    document.getElementById("reset_button").disabled = false;
	                    $("#reset_loader").hide();
	                } else if (result.code== "2"){
	                    $('#container').attr('class', 'alert alert-danger');
	                    $("#container").html("<?php echo ($display ? "Girdiğiniz şifreler uyuşmuyor" : "Girdiğiniz kullanıcı adı yanlış"); ?>");
	                    $('#result').animate({opacity: 1}, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0}, 1000);}, 1500);
	                    document.getElementById("reset_button").disabled = false;
	                    $("#reset_loader").hide();
	                }

	            });
});
});
</script>
</body>
</html>
<?php ob_flush(); ?>
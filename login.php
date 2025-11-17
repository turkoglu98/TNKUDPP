<?php 
include("globaldefine.php");
if(isset($_SESSION['giris'])){
echo '<script type="text/javascript">window.location.href="./index.php";</script>'; exit();
}
$sec_code = md5(uniqid());
$_SESSION['sec_code'] = $sec_code;
?>
<!DOCTYPE html>
<html>
<head>
	<title>TNKÜ - Dosya Paylasim Platformu - Giriş Yap</title>

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
      <div id="login_div" style="position: relative;">
      	<a href="index.php" style="color: #212529;text-decoration: none;"><h3>TNKÜ - Dosya Paylaşım Platformu</h3></a>
      	<h4 style="margin-left: 40px;margin-bottom: 5px;">Üye Girişi</h4>
      	<form class="form-inline" id="login_form" style="">
      		<input type="hidden" name="login_form" value="1">
 <div class="form-group mb-2">
 	<div class="loader" style="display: none;margin-right: 5px;" id="login_loader"></div>
    <label for="username" class="sr-only">Kullanıcı adı</label>
    <input type="text" name= "username" class="form-control" id="username" placeholder="Kullanıcı adı" required>
  </div>
  <div class="form-group mx-sm-3" style="position: relative;">
    <label for="inputPassword" class="sr-only" >Şifre</label>
    <div class="row" style="
    width: 230px;
    margin-top: 15px;
">
    	<div class="col-12">
<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Şifre" required>
</div>
<div class="col">
<a href="password_reset.php">Şifremi Unuttum</a></div>
</div>
  </div>
  <input type="hidden" name="token" value="<?php echo $sec_code; ?>">
  <button type="submit" class="btn btn-primary mb-2" id="login_button">Giriş Yap</button>
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
$( "#login_form" ).on( "submit", function( event ) {
  event.preventDefault();
  document.getElementById("login_button").disabled = true;
  $("#login_loader").show();
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
	                document.getElementById("login_button").disabled = false;
	                $("#login_loader").hide();
	                }
	                    
	                    }
	            }).done(function (data) {
	            	 var result = JSON.parse(data);
					if (result.code == "1") {
	            		$('#container').attr('class', 'alert alert-success');
	                    $("#container").html("Giriş Başarılı.");
	                    $('#result').animate({opacity: 1}, 1000);
	                    $("#login_div").slideUp("slow");
	                    setTimeout(function () {
	                        window.location = "./index.php";
	                    }, 2000);
	                } else if (result.code== "0"){
	                    $('#container').attr('class', 'alert alert-danger');
	                    $("#container").html("Kullanıcı Adı veya Şifre Yanlış");
	                    $('#result').animate({opacity: 1}, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0}, 1000);}, 1500);
	                    document.getElementById("login_button").disabled = false;
	                    $("#login_loader").hide();
	                }

	            });
});
});
</script>
</body>
</html>
<?php ob_flush(); ?>
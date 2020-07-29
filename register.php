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
	<title>TNKÜ - Dosya Paylaşım Platformu - Üye Ol</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="./js/jquery.min.js"></script>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<script src="./js/bootstrap.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.1.0/dist/ionicons/ionicons.esm.js"></script>
<style>
	body{background-color:#eee}
  html, body{ height:100%}
	.loader{border-radius:50%;border-top:5px solid #007cf9;border-bottom:5px solid transparent;border-right:5px solid #007cf9;width:25px;height:25px;-webkit-animation:spin 1s linear infinite;animation:spin 1s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}
	form input{margin-bottom:10px;}
	ion-icon {
  color: white;
}
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
  <div class="card-header"><img src="./img/success.png" width="24" height="24" style="margin-right: 5px;"><span style="vertical-align: middle;">Kayıt Başarılı</span></div>
  <div class="card-body">
    <p class="card-text">Üyeliğiniz başarıyla tamamlandı.</p>
    <p class="card-text">Giriş Şifreniz E-posta adresinize gönderildi.</p>
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
      <div id="login_div" style="position: relative;">
      	<a href="index.php" style="color: #212529;text-decoration: none;"><h3>TNKÜ - Dosya Paylaşım Platformu</h3></a>
        <h4 style="margin-left: 40px;margin-bottom: 15px;">Üye Ol</h4>
      	<form style="width: 350px;" id="register_form">
      		  <input type="hidden" name="register_form" value="1">
<div class="form-row">
    <div class="col">
      <input type="text" class="form-control" name="name" placeholder="Adınız" autocomplete="off" required>
    </div>
    <div class="col">
      <input type="text" class="form-control" name="surname" placeholder="Soyadınız" required>
    </div>
  </div>
  <input type="text" class="form-control" name="username" placeholder="Kullanıcı Adı" autocomplete="off" required>
  
   <div class="form-group">
    <input type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="E-posta" required>
      <small id="emailHelp" class="form-text text-muted"><b>Şifreniz E-posta adresinize gönderilecektir</b></small>
  </div>

  <input type="hidden" name="token" value="<?php echo $sec_code; ?>">
  <button type="submit" class="btn btn-primary mb-2" id="register_button">Üye Ol</button>
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
$( "#register_form" ).on( "submit", function( event ) {
  event.preventDefault();
  document.getElementById("register_button").disabled = true;
  $("#register_loader").show();
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
	                document.getElementById("register_button").disabled = false;
	                $("#register_loader").hide();
	                }
	                    
	                    }
	            }).done(function (data) {
	            	 var result = data;
					if (result == "1") {                    
	                    $("#login_div").slideUp("slow", function() {
							$("#success_div").fadeIn("slow");
						});
	                    
	                    //$('#result').animate({opacity: 1}, 1000);
	                } else if (result== "0"){
	                    $('#container').attr('class', 'alert alert-danger');
	                    $("#container").html("Üyeliğiniz tamamlanırken bir hata oluştu");
	                    $('#result').animate({opacity: 1}, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0}, 1000);}, 1500);
	                    document.getElementById("register_button").disabled = false;
	                    $("#register_loader").hide();
	                } else if (result== "3"){
	                    $('#container').attr('class', 'alert alert-danger');
	                    $("#container").html("Bu kullanıcı adı alınmış. Başka bir tane deneyin.");
	                    $('#result').animate({opacity: 1}, 1000);
	                    setTimeout(function () { $('#result').animate({ opacity: 0}, 1000);}, 1500);
	                    document.getElementById("register_button").disabled = false;
	                    $("#register_loader").hide();
	                }

	            });
});
});
</script>
</body>
</html>
<?php ob_flush(); ?>
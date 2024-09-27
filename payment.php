<?php 
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookie', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.entropy_length', 32);
ini_set('session.hash_function', 'sha256');
session_start();
if (empty($_SESSION['email']) || empty($_COOKIE['fname']) || empty($_COOKIE['sname'])) {
	setcookie('fname', '', time() - 3600);
	setcookie('email', '',time() - 3600);
	setcookie('sname', '', time() - 3600);
	session_destroy();
	header('location:logout.php');
}else {
	if (isset($_SESSION['email'])){
		session_regenerate_id(true);
		$_SESSION['email'] = true;
	}
	if (!isset($_SESSION['user_agent'])){
		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	} elseif($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']){
		session_unset();
		session_destroy();
	}
	if (!isset($_SESSION['ip_address'])) {
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
	} elseif ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
		session_unset();
		session_destroy();
	}
	include 'daraja_x/stk-initiate.php';
	$fname = ucfirst($_COOKIE['fname']);
	$name = $_COOKIE['fname'];
	$sname = ucfirst($_COOKIE['sname']);
	$profile = substr($fname, 0, 1) . substr($sname, 0, 1);
	$email = $_COOKIE['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="images/x-icon" href="includes/images/santi2.png">
	<link rel="stylesheet" type="text/css" href="includes/styles/payment.css">
	<title>Santi Health - Payment</title>
    </head>
   <body>
   <div class="container">
    <form action="payment.php" method='POST'>
    <div class="card__container">
        <div class="card">
            <div class="row">
                    <img src="includes/images/mpesa2.png" style="width:100%;height:10%;border-radius:10px">
                    <p style="color:white;line-height:1.7;">
					1. Enter the <b>phone number</b> and press "<b>Confirm and Pay</b>"</br>
					2. The amount to  be paid is <b>Ksh. <?php if(empty($_GET['price'])){echo htmlspecialchars($_POST['price']);} else echo $_GET['price'];?></b></br>
					<input type="hidden" name="price" value="<?php if(empty($_GET['price'])){echo htmlspecialchars($_POST['price']);} else echo $_GET['price'];?>">
					3. You will receive a popup on your phone. Enter your <b>MPESA PIN</b><br>
					4. You can also pay through our till number: <b>6061164</b></p>
					<?php if ($errmsg != ''): ?>
                        <p style="background: #cc2a24;padding: .8rem;color: #ffffff;"><?php echo $errmsg ?? NULL; ?></p>
                    <?php endif; ?>
            </div>
            <div class="row number">
                <div class="info">
                    <input type="hidden" name="orderNo" value="consultation"/>
                    <label for="cardnumber">Phone number</label>
                    <input id="cardnumber" type="text" name="phone_number" required maxlength="10" placeholder="07xxxxxxxx">
                </div>
            </div>
        </div> 
    </div>
    <div class="button">
        <button type="submit" name="submit"><i class="ion-locked"></i> Confirm and Pay</button>
    </div>
    </form>
    <p style="color:white;line-height:1.7;margin-top:2rem;position:relative;">Copyright @ <?php echo date("Y")?>  | All Rights Reserved | Santi Health Ltd.</p>
</div>
   </body>
</html>
<?php }?>
<?php
include 'daraja_x/stk-initiate.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="images/x-icon" href="includes/images/white.JPG">
	<title>Santi Health - Payment</title>
	<style type="text/css">
		@import url(https://fonts.googleapis.com.css?family=Lato:400,100,300,700,900);
		@import url(https://fonts.googleapis.com.css?family=Source+Code+Pro:400,200,300,500,600);
		* {
			box-sizing: border-box;
		}
		.container {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
			flex-direction: column;
		}
		html {
			background-color: #171A3D;
			font-family: Arial, Helvetica, sans-serif;
		}
		.price h1 {
			background-color: #171A3D;
			color: #18C2C0;
			letter-spacing: 2px;
			text-align: center;
		}
		.card {
			margin-top: 30px;
			margin-bottom: 30px;
			width: 520px;
		}
		.card .row{
			width: 100%;
			padding: 1rem 0;
			border-bottom: 1.2px solid #292C58;
		}
		.card .row.number {
			background-color: #242852;
		}
		.row > img{
			border-radius: 10px;
			object-fit: contain;
		}
		.cardholder .info .number {
			position: relative;
			margin-left: 40px;
		}
		.cardholder .info label, .number .info label {
			display: inline-block;
			letter-spacing: 0.5px;
			color: #8F92C3;
			width: 40%;
		}
		.cardholder .info input, .number .info input {
			display: inline-block;
			width: 50%;
			background-color: transparent;
			font-family: 'Source code Pro';
			border: none;
			outline: none;
			margin-left: 1%;
			color: white;
		}
		.cardholder .info input::placeholder, .number .info input::placeholder {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			color: #444880;
		}
		#cardnumber, #cardnumber::placeholder {
			letter-spacing: 2px;
			font-size: 16px;
		}
		.button button {
			font-size: 1.2rem;
			font-weight: 400;
			letter-spacing: 1px;
			width: 520px;
			background-color: #18C2C0;
			border: none;
			color: #fff;
			padding: 18px;
			border-radius: 5px;
			outline: none;
			cursor:pointer;
			transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1); 
		}
			.button button:hover {
				background-color: #15aeac; 
			}
			.button button:active {
				background-color: #139b99; 
			}
			.button button i {
				font-size: 1.2rem;
				margin-right: 5px; 
			}
        </style>
    </head>
   <body>
   <div class="container">
	<a href="dashboard.php"><button>Home</button></a>
    <form action="payment.php" method='POST'>
		<div class="price">
			<h1>Awesome, that's KES <?php echo $_COOKIE['price'] ?></h1>
		</div>
    <div class="card__container">
        <div class="card">
            <div class="row">
                    <img src="includes/images/mpesa2.jpg" style="width:50%;height:50%;margin:0 25%;border-radius:10px">
                    <p style="color:#8F92C3;line-height:1.7;">
					1. Enter the <b>phone number</b> and press "<b>Confirm and Pay</b>"</br>
					2. You will receive a popup on your phone. Enter your <b>MPESA PIN</b><br>
					3. You can also pay through our till number: <b>6061164</b></p>
					<?php if ($errmsg != ''): ?>
                        <p style="background: #cc2a24;padding: .8rem;color: #ffffff;"><?php echo $errmsg; ?></p>
                    <?php endif; ?>
            </div>
            <div class="row number">
                <div class="info">
                    <input type="hidden" name="orderNo" value="consultation" /> <!-- For testing purposes, we have added the value. This should proceed from your website -->
                    <label for="cardnumber">Phone number</label>
                    <input id="cardnumber" type="text" name="phone_number" maxlength="10"/>
                </div>
            </div>
        </div>
    </div>
    <div class="button">
        <button type="submit" name="submit"><i class="ion-locked"></i> Confirm and Pay</button>
    </div>
    </form>
    <p style="color:#8F92C3;line-height:1.7;margin-top:10rem;position:relative;">@ copyright <?php echo date("Y")?>  | All Rights Reserved | Santi Health Ltd.</p>
</div>
   </body>
</html>
<?php  ?>
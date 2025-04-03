<?php
require_once "config.php";
?>
<html>
   <head>
      <title>Paytm Blink Checkout - PHP</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   </head>
   <body>
   	<div class="container text-center">
      	<div class="shadow p-3 mb-5 bg-white rounded">
		<?php 
			if(!empty($_POST)){
			$checksum = (!empty($_POST['CHECKSUMHASH'])) ? $_POST['CHECKSUMHASH'] : '';
			unset($_POST['CHECKSUMHASH']);
			$verifySignature = PaytmChecksum::verifySignature($_POST, PAYTM_MERCHANT_KEY, $checksum);

			if($verifySignature){
			?>
				<h4 class="text-success text-left">Checksum is verified. Transaction details are below:</h4>	
				<table class="table table-bordered">
				<?php foreach($_POST as $key => $value){?>
					<tr><td><?php echo $key?></td><td><?php echo $value?></td></tr>
				<?php } ?>	
				</table>
			<?php } else {?>
			<h3 class="text-danger">Checksum is not verified.</h3>	
			<?php } ?>
		<?php } else {?>
			<h3 class="text-danger">Empty POST Response</h3>
		<?php } ?>
		</div>
	</div>
	</body>
</html>
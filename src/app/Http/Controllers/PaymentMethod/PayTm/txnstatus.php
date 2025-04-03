<?php
/**
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once "config.php";


$transactionResult= transactionStatus();
/* print_r($response); */
?>
<html>
   <head>
      <title>Paytm Js Checkout - PHP</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   </head>
   <body>
   	<div class="container text-center">
      	<div class="shadow p-3 mb-5 bg-white rounded">
           <h2>Transaction Status</h2>
           <table class="table table-bordered">
				<?php foreach($transactionResult['body'] as $key => $value){ ?>
					<tr><td><?php echo $key; ?></td><td><?php echo is_array($value)?json_encode($value):$value; ?></td></tr>
                    <?php } ?>
				</table>
		</div>
	</div>
	</body>
</html>
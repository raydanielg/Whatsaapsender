<?php
require_once "config.php";
?>
<html>
   <head>
      <title>Paytm Js Checkout - PHP</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"  crossorigin="anonymous">

      <script type="application/javascript" crossorigin="anonymous" src="<?php echo PAYTM_ENVIRONMENT; ?>/merchantpgpui/checkoutjs/merchants/<?php echo PAYTM_MID; ?>.js"></script>
	  <script type="application/javascript" crossorigin="anonymous" src="script.js"></script>
   </head>
   <body>
      <div class="container text-center">
      	<div class="shadow p-3 mb-5 bg-white rounded">
      		<h2>Paytm Blink Checkout - PHP</h2>
         	<h4>Make Payment</h4>
        	<p>You are making payment of ₹1</p>
	        <div class="btn-area">
	            <button type="button" id="JsCheckoutPayment" name="submit" class="btn btn-primary">Pay Now</button>
	        </div>
      	</div>
      </div>
      <script type="application/javascript">
         document.getElementById("JsCheckoutPayment").addEventListener("click", function(){
			openJsCheckoutPopup('<?php echo $result['orderId']?>','<?php echo $result['txnToken']?>','<?php echo $result['amount']?>');
         	}
         );
      </script>
   </body>
</html>
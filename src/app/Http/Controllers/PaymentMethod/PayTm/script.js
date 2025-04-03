function openJsCheckoutPopup(orderId, txnToken, amount)
{
var config = {
	"root": "",
	"flow": "DEFAULT",
	"data": {
		"orderId": orderId, 
		"token": txnToken, 
		"tokenType": "TXN_TOKEN",
		"amount": amount 
		},
		"merchant":{
		"redirect": true
	},
	"handler": {
	"notifyMerchant": function(eventName,data){
		console.log("notifyMerchant handler function called");
		console.log("eventName => ",eventName);
		console.log("data => ",data);
		}
	}
};
	if(window.Paytm && window.Paytm.CheckoutJS){
		// initialze configuration using init method 
		window.Paytm.CheckoutJS.init(config).then(function onSuccess() {
			// after successfully updating configuration, invoke checkoutjs
			window.Paytm.CheckoutJS.invoke();
		}).catch(function onError(error){
			console.log("error => ",error);
		});
}
}
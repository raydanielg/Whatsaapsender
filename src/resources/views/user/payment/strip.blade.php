@extends('user.layouts.app')

@push("style-push")
  

<style>
	/* Variables */
* {
  box-sizing: border-box;
}


form {
    width: 30vw;
    min-width: 500px;
    align-self: center;
    box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
    0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
    border-radius: 7px;
    padding: 40px;
    margin: 0  auto;
    background: var(--white);
}

.hidden {
  display: none;
}

#payment-message {
  color: rgb(105, 115, 134);
  font-size: 16px;
  line-height: 20px;
  padding-top: 12px;
  text-align: center;
}

#payment-element {
  margin-bottom: 24px;
}

/* Buttons and links */
button {
  background: #5469d4;
  font-family: Arial, sans-serif;
  color: #ffffff;
  border-radius: 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
  width: 100%;
}
button:hover {
  filter: contrast(115%);
}
button:disabled {
  opacity: 0.5;
  cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}
.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}
.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #5469d4;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #5469d4;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media only screen and (max-width: 600px) {
  form {
    width: 80vw;
    min-width: initial;
  }
}
</style>

@endpush
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="row gy-4">
          <div class="col-md-12">
            <div class="page-header-left">
              <h2>{{ $title }}</h2>
              <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.dashboard') }}">{{ translate("Dashboard") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.plan.create') }}">{{ translate("Buy Or Renew Plan") }}</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('user.plan.make.payment', $id) }}">{{ translate("Make Payment") }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"> {{ translate("Automatic Payment- Stripe") }} </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header pt-4 justify-content-center">
              <h4 class="card-title">{{translate($title)}}</h4>
            </div>
            <div class="card-body p-4">
              <form id="payment-form">
                <div id="link-authentication-element">
                  <!--Stripe.js injects the Link Authentication Element-->
                </div>
                <div id="payment-element">
                  
                </div>
                <button id="submit">
                  <div class="spinner hidden" id="spinner"></div>
                  <span id="button-text">{{ translate("Pay now") }}</span>
                </button>
                <div id="payment-message" class="hidden"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
<section>

</section>
@endsection

@push('script-push')
<script src="https://js.stripe.com/v3/"></script>
	<script>
		"use strict"
        const stripe = Stripe("{{$paymentMethod->payment_parameter->publishable_key}}");
		const items = [{ id: "xl-tshirt" }];

		let elements;

		initialize();
		checkStatus();

		document
		.querySelector("#payment-form")
		.addEventListener("submit", handleSubmit);

		let emailAddress = '';
		async function initialize() {
		const { clientSecret } = await fetch("{{route('user.payment.with.strip')}}", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({ items }),
			
		}).then((r) => r.json()).catch((error) => {
                console.log("Error:", error);
          });

		elements = stripe.elements({ clientSecret });
		
		const linkAuthenticationElement = elements.create("linkAuthentication");
		linkAuthenticationElement.mount("#link-authentication-element");

		const paymentElementOptions = {
			layout: "tabs",
		};

		const paymentElement = elements.create("payment", paymentElementOptions);
	     	paymentElement.mount("#payment-element");
		}

		async function handleSubmit(e) {
		e.preventDefault();
		setLoading(true);

		const { error } = await stripe.confirmPayment({
			elements,
			confirmParams: {
			return_url: "{{route('user.payment.with.strip.success')}}",
			receipt_email: emailAddress,
			},
		});

		if (error.type === "card_error" || error.type === "validation_error") {
			showMessage(error.message);
		} else {
			showMessage("An unexpected error occurred.");
		}

		setLoading(false);
		}

		async function checkStatus() {
	
			const clientSecret = new URLSearchParams(window.location.search).get(
				"payment_intent_client_secret"
			);

			if (!clientSecret) {
				return;
			}

		  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

			switch (paymentIntent.status) {
				case "succeeded":
				showMessage("Payment succeeded!");
				break;
				case "processing":
				showMessage("Your payment is processing.");
				break;
				case "requires_payment_method":
				showMessage("Your payment was not successful, please try again.");
				break;
				default:
				showMessage("Something went wrong.");
				break;
			}
		}

		// ------- UI helpers -------

		function showMessage(messageText) {
		const messageContainer = document.querySelector("#payment-message");

		messageContainer.classList.remove("hidden");
		messageContainer.textContent = messageText;

		setTimeout(function () {
			messageContainer.classList.add("hidden");
			messageText.textContent = "";
		}, 4000);
		}

		// Show a spinner on payment submission
		function setLoading(isLoading) {
		if (isLoading) {
			// Disable the button and show a spinner
			document.querySelector("#submit").disabled = true;
			document.querySelector("#spinner").classList.remove("hidden");
			document.querySelector("#button-text").classList.add("hidden");
		} else {
			document.querySelector("#submit").disabled = false;
			document.querySelector("#spinner").classList.add("hidden");
			document.querySelector("#button-text").classList.remove("hidden");
		}
		}
	</script>
@endpush

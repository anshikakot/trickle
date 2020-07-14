<?php 
/*
* Template Name: stripe
*/
 ?>




<!DOCTYPE html>
<html>
<head>
	<title>Stripe</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<?php if(isset($_POST['stripeToken'])){ 
global $stripe;
$current_user=get_current_user_id();
$stripe_id=get_user_meta($current_user,'stripe_customer_id',true);
try{
	$data=$stripe->customers->createSource(
	  $stripe_id,
	  ['source' => $_POST['stripeToken']]
	);
	#update user subscription data
	update_user_meta($current_user,'stripe_sources',json_encode($data));		

	$stripe_sources=get_user_meta($current_user,'stripe_sources',true);

	$charge = $stripe->charges->create([
	    'amount' => $_GET['amount']*100, // $15.00 this time
	    'currency' => 'usd',
	    'customer' => $stripe_id, // Previously stored, then retrieved
	]);

	#update transaction data
	?>
	<div>
		<div class="form-row">
	  		<div class="col-md-6">
				<h1>Thanks for subscription</h1>
			</div>
		</div>
	</div>
	<?php

} catch(Exception $e){
	$user_error=get_user_meta($current_user,'stripe_error',true);
	if(empty($user_error)){
		$user_error=array();
	}
	$user_error[]=date('Y-m-d h:i:s').":".$e->getMessage();
	update_user_meta($current_user,'stripe_error',$user_error);
	?>
	<div>
		<div class="form-row">
	  		<div class="col-md-6">
				<h1><?php echo $e->getMessage() ?></h1>
			</div>
		</div>
	</div>
	<?php
}

?>

<?php } else{ ?>	
<div>
	<div class="form-row">
  		<div class="col-md-6">
    		<label for="card-element">Elements</label>
    		<form action="" method="post" id="payment-form">
			  <div class="form-row">
			    <label for="card-element">
			      Credit or debit card
			    </label>
			    <div id="card-element">
			      <!-- a Stripe Element will be inserted here. -->
			    </div>

			    <!-- Used to display form errors -->
			    <div id="card-errors" role="alert"></div>
			  </div>

			  <input type="submit" class="submit btn btn-info" value="Submit Payment">
			</form>
  		</div>
	</div>
</div>

<script src="https://js.stripe.com/v3/"></script>

 <script type="text/javascript">
var stripe = Stripe('pk_test_51H32P5JtDALqulAjbWzcR87wmo0NtaBevv1Jt7oHnIVUl7UaenDvKERTEUlccLcHVs0Be6DBZmN2Xiur9xGHh0OM00cWxe3EWw');
var elements = stripe.elements();
var card = elements.create('card');

// Add an instance of the card UI component into the `card-element` <div>
card.mount('#card-element');

var stripeTokenHandler = function(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}

function createToken() {
  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server
      alert(result.token.id);
      stripeTokenHandler(result.token);
    }
  });
};

// Create a token when the form is submitted.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(e) {
  e.preventDefault();
  createToken();
});

 </script>
<?php } ?>
</body>
</html>


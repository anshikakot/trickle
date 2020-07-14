<?php

/**
* Template Name: blank template 
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */
get_header();

?>

<?php 	
if(isset($_POST['price'])){
	$current_user=get_current_user_id();
	try{
		global $stripe;
		$session = $stripe->checkout->sessions->create([
		  'payment_method_types' => ['card'],
		  'line_items' => [[
		    'price_data' => [
			      'unit_amount' => $_POST['price']*100,
			      'currency' => 'usd',
			      'product' => 'prod_Hdj0HsPtacbbED',
			      'recurring' => [
			        'interval' => 'month',
			      ],
			    ],
		    'quantity' => 1,
		    ]],
		  'mode' => 'subscription',
		  'success_url' => home_url().'?page_id=9&session_id={CHECKOUT_SESSION_ID}',
		  'cancel_url' => home_url().'?page_id=11',
		]);
		update_user_meta($current_user,'stripe_session_id',$session->id);
	} catch(Exception $e){
		echo $e->getMessage();
		$user_error=get_user_meta($current_user,'stripe_error',true);
		if(empty($user_error)){
			$user_error=array();
		}
		$user_error[]=date('Y-m-d h:i:s').":".$e->getMessage();
		update_user_meta($current_user,'stripe_error',$user_error);

	}					 

	//	print_r($session);
	?>
	<script src="https://js.stripe.com/v3/"></script>


	<script type="text/javascript">
	var stripe = Stripe('pk_test_51H32P5JtDALqulAjbWzcR87wmo0NtaBevv1Jt7oHnIVUl7UaenDvKERTEUlccLcHVs0Be6DBZmN2Xiur9xGHh0OM00cWxe3EWw');
			  stripe.redirectToCheckout({
			    // Make the id field from the Checkout Session creation API response
			    // available to this file, so you can provide it as argument here
			    // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
			    sessionId: '<?php echo  $session->id	 ?>'
			  }).then(function (result) {
			    // If `redirectToCheckout` fails due to a browser or network
			    // error, display the localized error message to your customer
			    // using `result.error.message`.
			  });
	</script>	
<?php } ?>


<main id="site-content" role="main">
	<div class="container" style="margin: 0 auto: width:500px; padding: 50px">
		<div>	
			<form method="post">
				<input type="number" name="price" name="">
				<button class="" id="checkout-button">	Pay </button>	
			</form>
		</div>
	</div>
</main>



<?php 	get_footer() ?>

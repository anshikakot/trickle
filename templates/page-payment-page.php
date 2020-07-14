<?php

/**
* Template Name: payment page
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

<main id="site-content" role="main">
	<div class="container" style="margin: 0 auto: width:500px; padding: 50px">
			
	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			?>
				<label>Enter Amount from 1-9$</label>
				<input type="number" min=1 max="9" name="amount" id='amount' value="">
				<button type="button" class="btn btn-primary pay" data-toggle="modal" data-target="#myModal">
				  Pay
				</button>
			<?php
		}
	}
	
	?>
	</div>		
</main><!-- #site-content -->


<?php get_footer(); ?>
<style type="text/css">
	iframe{
		width: 100% !important;
		border: 0;
	}
</style>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Payment Gateway</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

      	<?php 	
   //    	global $stripe;
			// 	$current_user=get_current_user_id();
   //    			$stripe_id=get_user_meta($current_user,'stripe_customer_id',true);
   //    			echo $stripe_id;
			// $subscription = $stripe->subscriptions->create([
			//   'customer' => $stripe_id,
			//   'items' => [[
			//     'price' => 'price_1H4N8dJtDALqulAjL6crij5r',
			//   ]]
			// ]);
			// print_r($subscription);
 ?>
        <!-- <iframe src="" style="width:100%; height: 500px"></iframe> -->
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
			    'amount' => $_POST['amount']*100, // $15.00 this time
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
		}
		else{
		?>


 		<div>
			<div class="form-row">
		  		<div class="col-md-6">
		    		<label for="card-element">Elements</label>
		    		<form action="?action=popup" method="post" id="payment-form">
		    			<input type="" name="amount" id='amountmodal' value="">
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
		<?php } ?>
		<script src="https://js.stripe.com/v3/"></script>
		<script src="<?php  bloginfo('template_directory') ?>/custom-stripe.js"></script>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!--

			Stripe\Customer Object
(
    [id] =&gt; cus_Hca6fLixS2nEpv
    [object] =&gt; customer
    [address] =&gt; 
    [balance] =&gt; 0
    [created] =&gt; 1594382562
    [currency] =&gt; 
    [default_source] =&gt; 
    [delinquent] =&gt; 
    [description] =&gt; test stripe user
    [discount] =&gt; 
    [email] =&gt; stripe@gmail.com
    [invoice_prefix] =&gt; FF5CD767
    [invoice_settings] =&gt; Stripe\StripeObject Object
        (
            [custom_fields] =&gt; 
            [default_payment_method] =&gt; 
            [footer] =&gt; 
        )

    [livemode] =&gt; 
    [metadata] =&gt; Stripe\StripeObject Object
        (
        )

    [name] =&gt; stripe
    [phone] =&gt; 
    [preferred_locales] =&gt; Array
        (
        )

    [shipping] =&gt; 
    [sources] =&gt; Stripe\Collection Object
        (
            [object] =&gt; list
            [data] =&gt; Array
                (
                )

            [has_more] =&gt; 
            [total_count] =&gt; 0
            [url] =&gt; /v1/customers/cus_Hca6fLixS2nEpv/sources
        )

    [subscriptions] =&gt; Stripe\Collection Object
        (
            [object] =&gt; list
            [data] =&gt; Array
                (
                )

            [has_more] =&gt; 
            [total_count] =&gt; 0
            [url] =&gt; /v1/customers/cus_Hca6fLixS2nEpv/subscriptions
        )

    [tax_exempt] =&gt; none
    [tax_ids] =&gt; Stripe\Collection Object
        (
            [object] =&gt; list
            [data] =&gt; Array
                (
                )

            [has_more] =&gt; 
            [total_count] =&gt; 0
            [url] =&gt; /v1/customers/cus_Hca6fLixS2nEpv/tax_ids
        )

)

-->
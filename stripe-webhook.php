<?php 
/*
* Template Name: stripe
*/

$endpoint_secret = 'whsec_TPvnnYansrdHv5d6KUj1rLQLwGxALuTa';

		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$event = null;

		try {
		  $event = \Stripe\Webhook::constructEvent(
		    $payload, $sig_header, $endpoint_secret
		  );
		} catch(Exception $e) {
		  // Invalid payload
		  http_response_code(400);
		}

		// Handle the checkout.session.completed event
		if ($event->type == 'checkout.session.completed') {
		 // echo "akil";
		 // print_r($event->data);
		 // die;
		  $session = $event->data->object;
		  global $wpdb;
		  $results=$wpdb->get_results("select * from ".$wpdb->prefix."usermeta where meta_key='stripe_session_id' and meta_value='".$session->id."'");
		  if(!empty($results)){
			$current_user=$results[0]->user_id;
			update_user_meta($current_user,'stripe_subscription_data',json_encode($event->data));
			update_user_meta($current_user,'stripe_session_id','');	  	
		  }
		  // Fulfill the purchase...
		  handle_checkout_session($session);
		}

		http_response_code(200);
		die;
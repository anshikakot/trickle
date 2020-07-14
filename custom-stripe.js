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
		      //alert(result.token.id);
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

	jQuery(document).ready(function(){
		jQuery('.pay').click(function(){
			jQuery('#amountmodal').val(jQuery('#amount').val());
			// jQuery('#myModal iframe').attr('src','<?php echo home_url() ?>/stripe/?amount='+jQuery('#amount').val())
		})

	})
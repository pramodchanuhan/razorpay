<?php
// Include Razorpay Checkout.js library
echo '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';

// Create a payment button with Checkout.js
echo '<button id="razorpay-button">Pay with Razorpay</button>';

// Add a script to handle the payment
echo '<script>
    document.getElementById("razorpay-button").onclick = function() {
        startPayment();
    };
    function startPayment() {
        var options = {
            key: "' . config('payment.idfc.api_key') . '", // Enter the Key ID generated from the Dashboard
            amount: ' . $arrayData['amount'] . ', // Amount in currency subunits (e.g., 50000 for 500 INR)
            currency: "' . $arrayData['currency'] . '",
            name: "Acme Corp",
            description: "Test transaction",
            image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
            order_id: "' . $arrayData['id'] . '", // Sample Order ID

            handler: function (response) {
                // Capture Razorpay response details
                alert("Payment ID: " + response.razorpay_payment_id);
                alert("Order ID: " + response.razorpay_order_id);
                alert("Signature: " + response.razorpay_signature);

                // Make AJAX request to process the payment
                // $.ajax({
                //     url: "' . route('payment.callback') . '",
                //     type: "POST",
                //     data: {
                //         _token: "' . csrf_token() . '", 
                //         razorpay_payment_id: response.razorpay_payment_id,
                //         razorpay_order_id: response.razorpay_order_id,
                //         razorpay_signature: response.razorpay_signature,
                //         order_id: "' . $arrayData['id'] . '" // Send the order ID if required
                //     },
                //     success: function(result) {
                //         swalSuccess("Payment Successful");
                //         setTimeout(() => {
                //             location.reload(); // Reload the page if necessary
                //         }, 3000);
                //     },
                //     error: function(xhr) {
                //         console.error(xhr);
                //         swalError("An error occurred during payment processing");
                //     }
                // });
            },

            prefill: {
                name: "Gaurav Kumar",
                email: "gaurav.kumar@example.com",
                contact: "9000090000"
            },
            notes: {
                address: "Razorpay Corporate Office"
            },
            theme: {
                color: "#3399cc"
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>';
?>

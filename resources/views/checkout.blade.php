<?php
// Set your callback URL
$callback_url = "http://127.0.0.1:8003/payment/callback";

// Include Razorpay Checkout.js library
echo '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';

// Get the CSRF token from Laravel
$csrf_token = csrf_token();

// Create a payment button with Checkout.js
echo '<button onclick="startPayment()">Pay with Razorpay</button>';

// Add a script to handle the payment
echo '<script>
    function startPayment() {
        var options = {
            key: "' . config('payment.idfc.api_key') . '", // Enter the Key ID generated from the Dashboard
            amount: ' . $arrayData['amount'] . ', // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            currency: "' . $arrayData['currency'] . '",
            name: "Acme Corp",
            description: "Test transaction",
            image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
            order_id: "' . $arrayData['id'] . '", // This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            prefill: {
                name: "Gaurav Kumar",
                email: "gaurav.kumar@example.com",
                contact: "9000090000"
            },
            notes: {
                address: "Razorpay Corporate Office",
                csrf_token: "' . $csrf_token . '" // Include the CSRF token in the request
            },
            theme: {
                "color": "#3399cc"
            },
            callback_url: "' . $callback_url . '"
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }
</script>';
?>

<?php
// Payment gateway settings
$gatewayUrl = 'https://your-payment-gateway-url.com';
$apiKey = 'your-api-key';

// Payment data
$paymentData = [
    'amount' => 100.00,
    'currency' => 'USD',
    'description' => 'Product Description',
    'customer_email' => 'customer@example.com',
    // Add other necessary fields as required by your payment gateway
];

// Initialize cURL session
$ch = curl_init($gatewayUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));

// Execute cURL session and get the response
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Handle the payment gateway response
$responseData = json_decode($response, true);
if ($responseData['success']) {
    // Payment was successful
    echo 'Payment successful!';
} else {
    // Payment failed
    echo 'Payment failed: ' . $responseData['message'];
}
?>

<form action="charge.php" method="POST">
<input type="text" name="name" placeholder="Name">
  <input type="email" name="email" placeholder="Email">
  <input type="number" name="amount" placeholder="Amount">
  
  <script
    src="https://checkout.stripe.com/checkout.js"
    class="stripe-button"
    data-key="pk_test_51MrRS8KFQASTXqUU4sj7ROLifjx1uKsZl3uiWjl8itBXwan6AxpdMrjtrzLtk3mB7Him5KPf388bzyMKjHLGeUgv00rhVN3lwX"
    data-name="Custom t-shirt"
    data-description="Your custom designed t-shirt"
    data-amount="10000"
    data-currency="gbp">
  </script>
</form>




//charge.php


<?php
require_once('vendor/autoload.php'); // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51MrRS8KFQASTXqUUOGm9n9d7qCSaRQ78dbiAMSct9O85AQcxvkbK4PhdHnl0XjY5h8IQRx9LvXBFmDgHiJjgHAew00KI8C49lM'); // Set your secret API key

$name = $_POST['name'];
$email = $_POST['email'];
$amount = $_POST['amount'];



$customer = \Stripe\Customer::create([
  'email' => 'customer@example.com',
  'source' => $_POST['stripeToken'],
]);

$charge = \Stripe\Charge::create([
  'customer' => $customer->id,
  'description' => 'Custom t-shirt',
  'amount' => 1000,
  'currency' => 'gbp',
]);

$capture = $charge->capture();

print_r($capture);


die;


try {
  $charge = \Stripe\Charge::create([
    'amount' => $amount,
    'currency' => 'usd',
    'source' => $_POST['stripeToken'],
    'description' => 'Payment for '.$name.' ('.$email.')'
  ]);
print_r($charge);

  // Handle success
} catch(\Stripe\Exception\CardException $e) {
  // Handle error
  print_r($e);
}

// Capture the payment after the charge is successful
$capture = $charge->capture(['amount_to_capture' => '1000']);

// Redirect the user to a success page
header('Location: success.php');
exit();
?>

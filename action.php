<?php

require_once('vendor/autoload.php');




\Stripe\Stripe::setApiKey('sk_test_51MpZFHSCidpuEbq29qWOGPnmDublbcyy6kxTXRAU6wrWYs9etbr0PYRC2JNpgPlm0uWuanK1hS4O02PRUZSgZv6u00IeNI56fS');


// Token is created using Stripe Checkout or Elements!
// Get the payment token ID submitted by the form:
//$token = $_POST['stripeToken'];
echo $token = $_POST['id'];
if(isset($token)){
  
  $charge = \Stripe\Charge::create([
    'amount' => 999,
    'currency' => 'usd',
    'description' => 'Example charge',
    'source' => $token,
  ]);
print_r($charge);
}else{
  echo "nothing";
}

   






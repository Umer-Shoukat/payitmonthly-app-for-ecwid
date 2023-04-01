<?php

$client_secret = "BBH5jmwMQBZdPJniDPacHxZar5RDzkug";
$iv = "eeurteu eryueryeureiweyruor";
$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
$tag = 0;
$app_secret_key = "secret_BjhgqPJFdP9Z8z7tz8k6g48zcjEm7gEd";

// If this is a payment request

if (isset($_POST["data"])) {

  function getEcwidPayload($app_secret_key, $data)
  {
    $encryption_key = substr($app_secret_key, 0, 16);
    // Decrypt payload
    $json_data = aes_128_decrypt($encryption_key, $data);

    $json_decoded = json_decode($json_data, true);

    return $json_decoded;
  }

  function aes_128_decrypt($key, $data)
  {
    // Ecwid sends data in url-safe base64. Convert the raw data to the original base64 first
    $base64_original = str_replace(array('-', '_'), array('+', '/'), $data);
    // Get binary data
    $decoded = base64_decode($base64_original);

    // Initialization vector is the first 16 bytes of the received data
    $iv = substr($decoded, 0, 16);

    // The payload itself is is the rest of the received data
    $payload = substr($decoded, 16);

    // Decrypt raw binary payload
    $json = openssl_decrypt($payload, "aes-128-cbc", $key, OPENSSL_RAW_DATA, $iv);
    //$json = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $payload, MCRYPT_MODE_CBC, $iv); // You can use this instead of openssl_decrupt, if mcrypt is enabled in your system

    return $json;
  }

  // Get payload from the POST and decrypt it
  $ecwid_payload = $_POST['data'];

  // The resulting JSON from payment request will be in $order variable
  $order = getEcwidPayload($client_secret, $ecwid_payload);
  //  echo $item_description = $order['cart']['order']['items'][0]['shortDescription'];
  //  exit;
  // $my_json_data  = json_encode($order);
  // echo $my_json_data;
  // exit;

  // Account info from merchant app settings in app interface in Ecwid CP
  $x_account_id = $order['merchantAppSettings']['merchantId'];

  $api_key = $order['merchantAppSettings']['apiKey'];

  $fullName = explode(" ", $order["cart"]["order"]["billingPerson"]["name"]);

  $firstName = $fullName[0];

  $lastName = $fullName[1];

  $customer_email = $order['cart']['order']['email'];

  $customer_mobile = $order['cart']['order']['billingPerson']['phone'];

  $order_value  = $order['cart']['order']['total'];

  $item_description = $order['cart']['order']['items'][0]['shortDescription'];

  $currency = $order['cart']['currency'];

  // Encode access token and prepare calltack URL template
  $ciphertext_raw = openssl_encrypt($order['token'], $cipher, $client_secret, $options = 0, $iv, $tag);
  $callbackPayload = base64_encode($ciphertext_raw);
  $callbackUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "?storeId=" . $order['storeId'] . "&orderNumber=" . $order['cart']['order']['orderNumber'] . "&callbackPayload=" . $callbackPayload;

  // Sign the payment request
  $signature = payment_sign($request, $api_key);
  $request["x_signature"] = $signature;

  include('form/form.php');
}

// Function to sign the payment request form
function payment_sign($query, $api_key)
{
  $clear_text = '';
  ksort($query);
  foreach ($query as $key => $value) {
    if (substr($key, 0, 2) === "x_") {
      $clear_text .= $key . $value;
    }
  }
  $hash = hash_hmac("sha256", $clear_text, $api_key);
  return str_replace('-', '', $hash);
}


//If we are returning back to storefront. Callback from payment

if (isset($_GET["callbackPayload"]) && isset($_GET["status"])) {

  // Set variables
  $client_id = "custom-app-75679751-4";
  $c = base64_decode($_GET['callbackPayload']);
  $token = openssl_decrypt($c, $cipher, $client_secret, $options = 0, $iv, $tag);
  $storeId = $_GET['storeId'];
  $orderNumber = $_GET['orderNumber'];
  $status = $_GET['status'];
  $returnUrl = "https://app.ecwid.com/custompaymentapps/$storeId?orderId=$orderNumber&clientId=$client_id";

  // Prepare request body for updating the order
  $json = json_encode(array(
    "paymentStatus" => $status,
    "externalTransactionId" => "transaction_" . $orderNumber
  ));

  // URL used to update the order via Ecwid REST API
  $url = "https://app.ecwid.com/api/v3/$storeId/orders/transaction_$orderNumber?token=$token";

  // Send request to update order
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json)));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  echo "<script>window.location = '$returnUrl'</script>";
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
  header('HTTP/1.0 403 Forbidden', TRUE, 403);
  die("<h2>Access Denied!</h2> This file is protected and not available to public.");
}

?>
<script>
  window.onload = () => {

    let orderValue = <?php echo $order_value; ?>

    // on page load get selected option and assign value
    let select = document.getElementById('installments');
    let installment = select.value;

    let payment = orderValue / installment;


    //console.log(payment)
    let round_value = Math.round((payment + Number.EPSILON) * 100) / 100;
    console.log(round_value)
    document.getElementById('todays-amount').value = round_value;
    document.getElementById('desposit-amount').value = round_value;

    // on changing selectbox value assiagn value

    select.addEventListener('change', () => {

      let select = document.getElementById('installments');
      let installment = select.value;

      let payment = orderValue / installment;
      let round_value = Math.round((payment + Number.EPSILON) * 100) / 100;

      document.getElementById('desposit-amount').value = round_value;
      document.getElementById('todays-amount').value = round_value;

      //console.log(round_value);

    });

  }
</script>
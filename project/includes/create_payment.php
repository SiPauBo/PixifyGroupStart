<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php

function createpayment($amount, $description)
{
    $clientId = "AWLnbE5Flos8TbpH0-kLjAtgHallDrflXTUosykoryMAbOtQvTWeckWyXhXHyyOtuW7cUavmCn1Ve0UK";
    $clientSecret = "EMNdRnmAfRNprRAw5Up9qt-l5wDl6wyamPv2MKaJIons8eBtPPDJB_MZOD4yL7t4Kx9lUoKuQY-epFi9";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, true);

    $headers = [
        "Accept: application/json",
        "Accept-Language: en_US",
        "Content-Type: application/x-www-form-urlencoded",
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (!$response) {
        echo "cURL Fehler: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    $response = json_decode($response, true);

    if (isset($response['access_token'])) {
        $accessToken = $response['access_token'];
        #echo "Access Token erfolgreich abgerufen: " . $accessToken;
    } else {
        echo "Fehler: Access Token konnte nicht abgerufen werden.<br>";
        echo "Antwort von PayPal: <pre>" . print_r($response, true) . "</pre>";
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    $paymentData = [
        "intent" => "sale",
        "redirect_urls" => [
            "return_url" => "http://localhost:9999/ITP4/PayPal_API_Test/succes.php",
            "cancel_url" => "http://localhost:9999/ITP4/PayPal_API_Test/cancel.php"
        ],
        "payer" => [
            "payment_method" => "paypal"
        ],
        "transactions" => [[
            "amount" => [
                "total" => $amount,
                "currency" => "EUR"
            ],
            "description" => $description
        ]]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/payments/payment");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));

    $response = curl_exec($ch);


    if (!$response) {
        echo "cURL Fehler bei der Zahlungserstellung: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    $response = json_decode($response, true);
    curl_close($ch);

    if (isset($response['links'])) {
        foreach ($response['links'] as $link) {
            if ($link['rel'] == 'approval_url') {
                #header("Location: " . $link['href']);
                echo '
                    <div class="d-flex justify-content-center align-items-center" style="height: 100vh; background-color: #f5f8fc;">
                      <div class="card shadow-lg text-center" style="width: 24rem; border-radius: 15px; overflow: hidden;">
                        <div class="card-body">
                          <h5 class="card-title fw-bold mb-3">Proceed to Payment</h5>
                          <p class="card-text text-muted">
                            You are about to leave <strong>Pixify</strong> and will be redirected to PayPal to complete your payment securely. 
                          </p>
                          <a href="' . $link['href'] . '" class="btn btn-primary btn-lg" style="border-radius: 25px;">
                            Continue to PayPal
                          </a>
                        </div>
                      </div>
                   </div>
                ';
                exit;
            }
        }
    }

    echo "Fehler: Zahlung konnte nicht erstellt werden.";
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
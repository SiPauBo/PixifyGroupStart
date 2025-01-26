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
function success($description) {
    if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];

        // PayPal API Credentials
        $clientId = "AWLnbE5Flos8TbpH0-kLjAtgHallDrflXTUosykoryMAbOtQvTWeckWyXhXHyyOtuW7cUavmCn1Ve0UK";
        $clientSecret = "EMNdRnmAfRNprRAw5Up9qt-l5wDl6wyamPv2MKaJIons8eBtPPDJB_MZOD4yL7t4Kx9lUoKuQY-epFi9";

        // Access Token abrufen
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
            echo "Fehler beim Abrufen des Access Tokens: " . curl_error($ch);
            curl_close($ch);
            exit;
        }

        $response = json_decode($response);
        if (isset($response->access_token)) {
            $accessToken = $response->access_token;
        } else {
            echo "Fehler: Kein Access Token erhalten. Antwort von PayPal: <pre>" . print_r($response, true) . "</pre>";
            curl_close($ch);
            exit;
        }
        curl_close($ch);

        // Zahlung ausführen
        $url = "https://api-m.sandbox.paypal.com/v1/payments/payment/$paymentId/execute";

        $executeData = [
            "payer_id" => $payerId
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($executeData));

        $response = curl_exec($ch);
        if (!$response) {
            echo "Fehler beim Ausführen der Zahlung: " . curl_error($ch);
            curl_close($ch);
            exit;
        }

        $response = json_decode($response, true);
        curl_close($ch);

        if (isset($response['state']) && $response['state'] == 'approved') {
            echo "<h1>Kauf von $description erfolgreich</h1>";
            echo "<p>Transaktions-ID: " . $response['id'] . "</p>";
        } else {
            echo "<h1>Zahlung fehlgeschlagen</h1>";
            echo "<p>Fehlerdetails: <pre>" . print_r($response, true) . "</pre></p>";
        }
    } else {
        echo "<h1>Zahlung konnte nicht ausgeführt werden</h1>";
    }
}
?>

<div class="card" style="width: 18rem; margin-top: 20px; text-align: center;">
    <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" class="card-img-top" alt="PayPal logo">
    <div class="card-body">
        <h5 class="card-title">Payment Successful</h5>
        <p class="card-text">Your transaction has been completed. Click the button below to go back to Pixify.</p>
        <a href="http://yourwebsite.com/index.php" class="btn btn-primary">Back to Pixify</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
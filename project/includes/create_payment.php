<?php
require_once '../includes/db_connection.php';

function createpayment($amount, $description, $plan)
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
    $response = json_decode($response, true);
    curl_close($ch);

    if (!isset($response['access_token'])) {
        die("Fehler: Access Token konnte nicht abgerufen werden.");
    }

    $accessToken = $response['access_token'];

    $paymentData = [
        "intent" => "sale",
        "redirect_urls" => [
            "return_url" => "http://localhost/Pixify/project/includes/succes.php?plan=$plan",
            "cancel_url" => "http://localhost/Pixify/project/includes/cancel.php"
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
    $response = json_decode($response, true);
    curl_close($ch);

    if (isset($response['links'])) {
        foreach ($response['links'] as $link) {
            if ($link['rel'] == 'approval_url') {
                header("Location: " . $link['href']);
                exit;
            }
        }
    }

    die("Fehler: Zahlung konnte nicht erstellt werden.");
}
?>

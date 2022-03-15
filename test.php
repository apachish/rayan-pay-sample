<?php
    require_once("PaymentRequest.php");
    $client = new SoapClient("https://pms.rayanpay.com/pg/services/webgate/wsdl", [
        'encoding' => 'UTF-8',
        "location" => "https://pms.rayanpay.com/pg/services/webgate/wsdl",
        'trace' => 1,
        "exception" => 1,
    ]);

    echo "<pre>";
    print_r($client->__getFunctions());
    echo "</pre>";
    echo "<pre>";

    print_r($client->__getTypes());
    echo "</pre>";
    exit;

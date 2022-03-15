<?php
session_start();


require_once("rayanpay.php");

$_SESSION = [];
$rayan_pay = new rayanpay();
$validation = $rayan_pay->validationForm($_POST);
if (!empty($validation)) {
    $_SESSION['error'] = $validation;
    @header('Location: ' . $rayan_pay->getUrl());
    exit;
}

$type = !empty($_POST['type']) ? $_POST['type'] : "rest";
$merchant_id = !empty($_POST['MerchantID']) ? $_POST['MerchantID'] : "";
$description = !empty($_POST['Description']) ? $_POST['Description'] : "";
$price = !empty($_POST['Amount']) ? $_POST['Amount'] : 0;
$email = !empty($_POST['Email']) ? $_POST['Email'] : "";
$mobile = !empty($_POST['Mobile']) ? $_POST['Mobile'] : "";
try {
    $rayan_pay->MerchantID = $merchant_id;
    $rayan_pay->Description = $description;
    $rayan_pay->Amount = $price;
    $rayan_pay->email = $email;
    $rayan_pay->mobile = $mobile;
    $rayan_pay->type = $type;
    $rayan_pay->CallbackURL = $rayan_pay->getUrl() . "verify.php";

    $result_start = $rayan_pay->request();
    if ($result_start['Status'] == 100) {
        $rayan_pay->redirect($result_start["StartPay"]);
    } else {
        $response = $result_start;
        include "layout.php";
    }
} catch (HttpRequestException $exception) {
    $rayan_pay->dd($exception);
} catch (Exception $exception) {
    $rayan_pay->dd($exception);
}



<?php

require_once("rayanpay.php");
$response = [];
$rayan_pay = new rayanpay();
$rayan_pay->Authority = !empty($_GET['Authority'])?$_GET['Authority']:"";
if ($_GET['Status'] === "OK") {
    try {
        $response = $rayan_pay->verify();
    } catch (Exception $exception) {
        $rayan_pay->dd($exception);
    }
} else {
    $response = $rayan_pay->notVerify();

    $_SESSION['error'][] = "پرداخت ناموفق";
}
include "layout.php";
?>
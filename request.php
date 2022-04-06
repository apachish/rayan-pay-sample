<?php
session_start();


require_once("rayanpay.php");

$_SESSION = [];
$rayan_pay = new rayanpay();
/*
 *
 *  چک کردن داده ارسالی موبایل،ایمیل ،مبلغ و فیلد های اجباری بررسی می شود
 */
$validation = $rayan_pay->validationForm($_POST);
/*
 * درصورت خطا در نشست قرار گرفته و به صفحه اصلی می رود
 */
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

    /*
     * درخواست که در صورت موفقیت آمیز بودن برابر 100
     * در غیر این صورت عددی منفی میباشد
     */
    $result_start = $rayan_pay->request();
    /*
     * درصورت ۱۰۰ باشد ریدایرکت می شود به آدرس
     * https://pms.rayanpay.com/pg/startpay/$Authority
     */
    if ($result_start['Status'] == 100) {
        $rayan_pay->redirect($result_start["StartPay"]);
    } else {
        /*
         * درصورت منفی بودن بر اساس کد خطا پیام نمایش داده می شود
         */
        $response = $result_start;
        include "layout.php";
    }
} catch (HttpRequestException $exception) {
    $rayan_pay->dd($exception);
} catch (Exception $exception) {
    $rayan_pay->dd($exception);
}



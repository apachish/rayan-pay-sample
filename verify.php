<?php

require_once("rayanpay.php");
$response = [];
$rayan_pay = new rayanpay();
$rayan_pay->Authority = !empty($_GET['Authority'])?$_GET['Authority']:"";
/*
 * بعد از پایان عملیات پرداخت، رایان پی کاربر را به این صفحه بازمیگرداند چون در داده ازسالی این میسر مشخص کرده بودیم.
رایان پی پارامترهای Authority و Status را به صورت QueryString بازمیگردانند که با
$_GET
قابل دریافت می باشد
.Status دارای دو مقدار ثابت “OK “و “NOK “است. درصورتی که این
پارامتر دارای مقدار “NOK “باشد تراکنش ناموفق و نیازی به فراخوانی متد verify ن می
باشد و تراکنش در وضعیت نهایی ناموفق قرار دارد و صفحه خطا مشاهد می کند. پذیرنده تراکنش معادل را با استفاده از Authority ارسالی
می تواند بازیابی کند.
در صورت موفق بودن تراکنش)دریافت پاسخ “OK "در پارامتر Status )پذیرنده موظف است جهت تکمیل
تراکنش متد verify را فراخوانی میکنیم و کد پیگیری RefID نمایش می دهیم
 */
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
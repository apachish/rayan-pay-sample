<!DOCTYPE html>
<html>
<head>
    <title>تست پرداخت رایان پی</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<style>
    input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #45a049;
    }

    div {
        border-radius: 5px;
        background-color: #f2f2f2;
        padding: 20px;
    }

    .help {
        font-size: 11px;
    }
</style>
<body>

<h3>Rayan pay</h3>
<?php
session_start();

if (!empty($_SESSION["error"])) {
    foreach ($_SESSION["error"] as $error) {
        ?>

        <div class="alert alert-danger text-right" dir="rtl" role="alert">
            <?= $error ?>
        </div>
        <?php
    }
}
?>
    <p dir="rtl">
این صفحه مواردی است که فرم خرید شما برای ادرس request.php  ارسال می کنید. مثلا شما فرم فاکتوری در سیستم خود دارید  مبلغ ان و شماره سفارش ان به اضافه اطلاعات  کاربریتان که نمونه در این فرم زده شده شما برای فایل request.php ارسال کرد تا به درگاه بانک وصل شود.
</p>
<div>
    <form action="./request.php" method="post">
        <label for="baseUrl">Type Connection<span class="text-danger">*</span> </label>
        <select class="form-control" name="type">
            <option  selected value="soap">Soap</option>
            <option value="rest">Rest</option>
        </select>
        <span class="help-block" dir="rtl">نوع اتصال به سرور های رایان پی</span>
        <label for="MerchantID">MerchantID <span class="text-danger">*</span></label>
        <input type="text" id="MerchantID" name="MerchantID" class="form-control" value="0cf77558-da05-4326-82e8-2201f86a2ddf" placeholder="Your MerchantID..">
        <span class="help-block" dir="rtl">شناسه شما از شرکت رایان پی.</span>

        <label for="Mobile">Mobile </label>
        <input type="text" id="Mobile" name="Mobile" class="form-control" placeholder="Your Mobile..">
        <span class="help-block" dir="rtl">شماره تلفن مورد نظر وارد نکنید یا به این صورت 989120001122 کنید</span>

        <label for="Email">Email </label>
        <input type="Email" id="Email" name="Email" class="form-control" placeholder="Your Email..">
        <span class="help-block" dir="rtl">ایمیل مورد نظر وارد نکنید یا به این صورت a@gmail.com کنید</span>

        <label for="description">Description </label>
        <textarea id="description" name="Description" class="form-control" placeholder="Your description.."></textarea>
        <span class="help-block" dir="rtl">توضیحات به صورت json وارد شود مثل زیر {"name":"shahriar","lastname":"pahlevansadgh"}</span>

        <label for="Amount">Amount <span class="text-danger">*</span></label>
        <input type="text" id="Amount" name="Amount" class="form-control" placeholder="Your Amount..">
        <span class="help-block" dir="rtl">مبلغ مورد نظر را   به ریال وارد کنید.</span>

        <input type="submit" value="تست درگاه پرداخت">
    </form>
</div>

</body>
</html>



<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>تست پرداخت رایان پی</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/pricing/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/4.0/examples/pricing/pricing.css" rel="stylesheet">
</head>

<body>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal">Rayan pay</h5>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4"></h1>
    <p class="lead"></p>
</div>
<div class="container">
    <div class="card-deck mb-3 text-center ml-5 mr-5 mt-3">
        <div class="card mb-4 box-shadow">
            <div class="card-header">
                <?php
                if ($response && ($response["Status"] === "OK" || $response["Status"] === 100)) {
                    ?>
                    <h4 class="my-0 font-weight-normal">پرداخت شما با موفقیت انجام شد</h4>
                <?php } else { ?>
                    <h4 class="my-0 font-weight-normal">پرداخت شما ناموفق بود است</h4>
                <?php } ?>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title">
                    <?php
                    if ($response && ($response["Status"] === "OK" || $response["Status"] === 100)){
                        ?>
                    <img src="receipt_success.svg" width="35%" height="35%"></h1>
                    <?php }else{ ?>

                    <img src="receipt_cancel.svg" width="35%" height="35%"></h1>
                <?php } ?>

                <ul class="list-unstyled mt-3 mb-4 text-xs-right" dir="rtl">

                    <?php
                    if ($response) {
                        if(!empty($response["RefID"]))
                            echo "<li> کد پیگیری   : " . $response["RefID"] . "</li>";
                        if(!empty($response["Authority"]))
                            echo "<li> کد ارجاع   : " . $response["Authority"] . "</li>";
                        echo "<li>مبلغ : " . $response["Amount"] . " ریال </li>";
                        if(!empty($response["Mobile"]))
                            echo "<li>تلفن همراه : " . $response["Mobile"] . "</li>";
                        if(!empty($response["Email"]))
                            echo "<li>تلفن همراه : " . $response["Email"] . "</li>";
                        if(!empty($response["Description"]))
                            echo "<li>توضیحات : " . $response["Description"] . "</li>";
                        if ($response["Status"] != 200) {
                            echo "<li> کد وضعیت وب سرویس : " . $response["Status"] . "</li>";
                            echo "<li> پیام وب سرویس : " . $response["Message"] . "</li>";
                        }
                    }
                    ?>
                </ul>
                <button type="button" onclick="location.href = '<?= $rayan_pay->getUrl() ?>';"
                        class="btn btn-lg btn-block btn-outline-primary">بازگشت صفحه تست پرداخت
                </button>
            </div>
        </div>
    </div>

    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
            <div class="col-12 col-md">
                <img class="mb-2" src="https://apachish.ir/images/logo.png" alt="" width="24" height="24">
                <small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
            </div>
        </div>
    </footer>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../assets/js/vendor/popper.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<script src="../../assets/js/vendor/holder.min.js"></script>
<script>
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>
</body>
</html>
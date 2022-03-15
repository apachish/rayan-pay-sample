<?php
require_once "PaymentRequest.php";
require_once "PaymentVerification.php";

class rayanpay
{
    private $address_soap = "https://pms.rayanpay.com/pg/services/webgate/wsdl";
    private $address_ref = "https://pms.rayanpay.com/pg/startpay/";
    private $address_rest = "https://pms.rayanpay.com/api/v2/ipg/paymentRequest";
    private $address_rest_verify = "https://pms.rayanpay.com/api/v2/ipg/paymentVerification";

    public $Authority = "";
    public $MerchantID = "";
    public $Description = "";
    public $Amount = "";
    public $mobile = "";
    public $email = "";
    public $CallbackURL = "";
    public $type = "rest";

    /*
     * تابع ذخیره سازی داده های شناسه و نام کاربری و پسورد  چون از ورودی دریافت می شود  و زمان بازگشت از بانک دوباره توکن گرفته می شود  در انجا از فایل خوانده می شود
     */
    private function saveStorage()
    {
        if (!empty($this->MerchantID) && !empty($this->type)) {
            $this->MerchantID = $this->MerchantID;
            $this->type = $this->type;
            $this->Description = $this->Description;
            $this->Amount = $this->Amount;
            $this->mobile = $this->mobile;
            $this->email = $this->email;
            $text = file_get_contents("storage.txt");
            $data = json_decode($text, true);
            /*
             * Authority مقداری برای اینکه اگر چند شناسه کاربری وارد شد در زمان بازگشت به شناسه کاربری درست وصل شود
             */
            $data[$this->Authority] = [
                'MerchantID' => $this->MerchantID,
                'type' => $this->type,
                'Description' => $this->Description,
                'Amount' => $this->Amount,
                'mobile' => $this->mobile,
                'email' => $this->email,
            ];
            $text = json_encode($data);
            $mystore = file_put_contents("storage.txt", $text);


        }
    }

    /*
     * برای خواندن فایل ذخیره شده و پر کردن مقدار شناسه و نام کاربری و پسورد
     */
    private function readStorage()
    {
        $text = file_get_contents("storage.txt");
        $data = json_decode($text, true);

        $this->MerchantID = !empty($data[$this->Authority]['MerchantID']) ? $data[$this->Authority]['MerchantID'] : $this->MerchantID;
        $this->type = !empty($data[$this->Authority]['type']) ? $data[$this->Authority]['type'] : $this->type;
        $this->Description = !empty($data[$this->Authority]['Description']) ? $data[$this->Authority]['Description'] : $this->Description;
        $this->mobile = !empty($data[$this->Authority]['mobile']) ? $data[$this->Authority]['mobile'] : $this->mobile;
        $this->email = !empty($data[$this->Authority]['email']) ? $data[$this->Authority]['email'] : $this->email;
        $this->Amount = !empty($data[$this->Authority]['Amount']) ? $data[$this->Authority]['Amount'] : $this->Amount;
    }


    private function soap_check()
    {
        return (extension_loaded('soap')) ? true : false;
    }

    private function curl_check()
    {
        return (function_exists('curl_version')) ? true : false;
    }

    /**
     * تابعی برای مشخص کردن پیام خطا با استفاده از کد بازگشتی از درخواست پاسخ
     * @param $error
     * @param $method
     * @param $prepend
     * @return string
     */
    private function error_message($code, $cb, $request = false)
    {
        if (empty($cb) && $request === true) {
            return "لینک بازگشت ( CallbackURL ) نباید خالی باشد";
        }

        $error = array(
            100 => "عملیات با موفقیت انجام شده است",
            -1 => "اطلاعات ارسال شده ناقص است.",
            -2 => "IP يا Merchant Code پذيرنده صحيح نيست.",
            -3 => "با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد.",
            -11 => "درخواست مورد نظر يافت نشد.",
            -21 => "هيچ نوع عمليات مالي براي اين تراكنش يافت نشد.",
            -22 => "تراكنش نا موفق مي باشد.",
            -33 => "رقم تراكنش با رقم پرداخت شده مطابقت ندارد.",
            -40 => "اجازه دسترسي به متد مربوطه وجود ندارد.",
            -41 => "اطلاعات ارسال شده مربوط به AdditionalData غيرمعتبر ميباشد.",
            -100 => "در انتظار پرداخت.",
            -101 => "آدرس بازگشت مشتری خالی است.",
            -102 => "در پرداخت خطایی رخ داده است.",
            -103 => "وضعیت پرداخت جهت تایید نادرست است.",
            -104 => "فروشگاهی با شناسه ارسالی یافت نشد.",
            -105 => "شناسه مرجع تراکنش اشتباه است",
            -106 => "خطای تایید پرداخت.",
            -107 => "وضعیت پرداخت صحیح نیست.",
            -109 => "فروشگاه غیر فعال است.",
            -110 => "شناسه ارسال شده نامعتبر است.",
            -111 => "پرداخت با شناسه ارسالی یافت نشد.",
            -112 => "فرمت توضیحات اشتباه است.",
            -113 => "فرمت موبایل اشتباه است.",
        );

        if (array_key_exists("{$code}", $error)) {
            return $error["{$code}"];
        } else {
            return "خطای نامشخص هنگام اتصال به درگاه رایان مهر";
        }
    }

    public function redirect($url)
    {
        header("Location: " . $url, true, 301);
        exit;
    }

    /*
     * تابع درخواست شروع و اتصال به درگاه بانک می باشد که در صورت درست بودن موارد ارسالی بدون خطا به درگاه رفته
     */
    public function request()
    {
        $Status = 0;
        $StartPayUrl = "";
        if ($this->type == "soap" && $this->soap_check() === true) {

            $client = new SoapClient($this->address_soap, [
                'encoding' => 'UTF-8',
                "location" => $this->address_soap,
                'trace' => 1,
                "exception" => 1,
            ]);

            $paymentRequest = new PaymentRequest();
            $paymentRequest->setMerchantID($this->MerchantID);
            $paymentRequest->setAmount((int)$this->Amount);
            $paymentRequest->setDescription($this->Description);
            $paymentRequest->setEmail($this->email);
            $paymentRequest->setMobile($this->mobile);
            $paymentRequest->setCallbackURL($this->CallbackURL);
            $result = $client->PaymentRequest(["paymentRequest" => $paymentRequest]);
            if (!isset($result->PaymentRequestResult)) return [];
            $Status = (isset($result->PaymentRequestResult->Status) && $result->PaymentRequestResult->Status != "") ? $result->PaymentRequestResult->Status : 0;
            $this->Authority = (isset($result->PaymentRequestResult->Authority) && $result->PaymentRequestResult->Authority != "") ? $result->PaymentRequestResult->Authority : "";
            $StartPayUrl = ($this->Authority != "") ? $this->address_ref . $this->Authority : "";

        } elseif ($this->type == "rest" && $this->curl_check() === true) {
            $paramter = [
                "merchantID" => $this->MerchantID,
                "amount" => (int)$this->Amount,
                "description" => $this->Description,
                "email" => $this->email,
                "mobile" => $this->mobile,
                "callbackURL" => $this->CallbackURL

            ];
            list($response, $http_status) = $this->getResponse($this->address_rest, array_filter($paramter));
            $Status = (isset($response->status) && $response->status != "") ? $response->status : 0;
            $this->Authority = (isset($response->authority) && $response->authority != "") ? $response->authority : "";
            $StartPayUrl = ($this->Authority != "") ? $this->address_ref . $this->Authority : "";
        }
        if ($this->Authority) {
            $this->saveStorage();
        }
        return array(
            "Method" => $this->type,
            "Status" => $Status,
            "Amount" => $this->Amount,
            "Mobile" => $this->mobile,
            "Email" => $this->email,
            "Description" => $this->Description,
            "Message" => $this->error_message($Status, $this->CallbackURL, true),
            "StartPay" => $StartPayUrl,
            "Authority" => $this->Authority
        );
    }

    /*
     * تابع درخواست  تایید بود که با توجه به گذاشتن شماره سفارش در ادرس بازگشتی در این تلبع بررسی شده و در صورت درست بودن پول از حساب کاربر کم می شود
     */
    public function verify()
    {
        $this->readStorage();
        $Status = 0;
        $Message = "";
        $RefID = "";


        if ($this->type == "soap" && $this->soap_check() === true) {


            $client = new SoapClient($this->address_soap, [
                'encoding' => 'UTF-8',
                "location" => $this->address_soap,
                'trace' => 1,
                "exception" => 1,
            ]);
            $payment_verification = new PaymentVerification();
            $payment_verification->setMerchantID($this->MerchantID);
            $payment_verification->setAmount($this->Amount);
            $payment_verification->setAuthority($this->Authority);
//            $this->dd(                ["PaymentVerificationRequest" => $payment_verification]);
            $result = $client->PaymentVerification(
                ["PaymentVerificationRequest" => $payment_verification]
            );

            $Status = isset($result->PaymentVerificationResult->Status) ? $result->PaymentVerificationResult->Status : 0;
            $RefID = (isset($result->PaymentVerificationResult->RefID)) ? $result->RefID : "";
            $Message = $this->error_message($Status, "", "");


        } elseif ($this->type == "rest" && $this->curl_check() === true) {
            $data = [
                "MerchantID" => $this->MerchantID,
                'Amount' => (int)$this->Amount,
                'Authority' => $this->Authority
            ];
            list($response, $http_status) = $this->getResponse($this->address_rest_verify, $data);
            $Status = (isset($response->status) && $response->status != "") ? $response->status : 0;
            $RefID = (isset($response->refID) && $response->refID != "") ? $response->refID : "";
            $Message = $this->error_message($Status, "", "", false);
        }

        return array(
            "Method" => $this->type,
            "Status" => $Status,
            "Message" => $Message,
            "Amount" => $this->Amount,
            "Mobile" => $this->mobile,
            "Email" => $this->email,
            "Description" => $this->Description,
            "RefID" => $RefID,
            "Authority" => $this->Authority
        );
    }

    public function notVerify()
    {
        $this->readStorage();
        $Status = 0;
        $Message = "";

        return array(
            "Method" => $this->type,
            "Status" => $Status,
            "Message" => $Message,
            "Amount" => $this->Amount,
            "Mobile" => $this->mobile,
            "Email" => $this->email,
            "Description" => $this->Description,
            "Authority" => $this->Authority
        );
    }


    /*
     * برای چک کردن موارد ارسالی در فرم که عدد وارد شود خالی نباشد
     */
    public function validationForm($data)
    {
        $error = [];
        if (empty($data['Amount']) || empty($data['MerchantID'])) {
            $error['fill'] = "فیلد های ستاره دار اجباری می باشد";
        }
        if (!filter_var($data['Amount'], FILTER_VALIDATE_INT)) {
            $error["Amount"] = "مقدار  مبلغ ارسالی عدد باشد.";
        }

        if ($data['Amount'] <= 1000) {

            echo $error["price-gt"] = "مقدار مبلغ ارسالی بزگتر از 1000 باشد";
        }

        if ($data['Mobile'] && !$this->perfix_mobile($data['Mobile'])) {

            echo $error["Mobile"] = " شماره موبایل باید با 98 شروع شود و یا تعداد اعداد وارد شده موبایل درست نیست";
        }

        if ($data['Email'] && !filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {

            echo $error["Email"] = " ایمیل وارد شده صحیح نمی باشد";
        }
        return $error;
    }

    /*
     * تابع بررس شماره موبایل با ۹۸ شروع شود
     */
    public function perfix_mobile($phone_number)
    {

        $pattern = "/^989[0-9]{9}$/";
        if (preg_match($pattern, $phone_number)) {
            return true;
        }
        return false;

    }


    /**
     * تابعی برای ارسال درخواست به سرور رایان پی با تابع curl
     * @param string $url ادرس درخواست
     * @param array $data داده ارسالی  در درخواست
     * @param array $header مقدار ارایه ست شد در هد  درخواست
     * @return bool|string
     */
    public function getResponse($url, array $data)
    {
        /*
         * داده ارسالی در داخل بدنه درخواست
         */
        $jsonData = json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $response = json_decode($response);
        return [$response, $http_status];
    }

    /*
     * تابعی برای دریافت آدرس اجرای محل پروژه
     */
    public function getUrl()
    {
        $protocl = "http:";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocl = "https://";
        }
        $url = $protocl . '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

        /*
         * برای اینکه ادرس از مسیر مرور گر برداشته شده احتمال دارد اخرش به صورت پیش فرض / باشد اگر نبود گذاشته شود
         */
        if (substr($url, -1) != "/")
            $url = $url . "/";
        return $url;
    }

    public function dd($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }


}

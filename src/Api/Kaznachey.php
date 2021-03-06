<?php

namespace SerJoga\Kaznachey\Api;

class Kaznachey
{

    protected $_serverUrl = 'http://payment.kaznachey.net/api/PaymentInterface/';
    protected $_merchantSecretKey;
    protected $_merchantGuid;

    public function __construct($merchantSecretKey, $merchantGuid)
    {
        $this->changeAccount($merchantSecretKey,$merchantGuid);
    }

    public function getMerchantInfo()
    {
        //Формируем массив запроса
        $request = [
            // Идентификатор мерчанта
            'MerchantGuid' => $this->_merchantGuid,
            //  Формируем подпись запроса md5 ({Идентификатор мерчанта}.{секретный ключ мерчанта})
            'Signature' => md5(strtoupper($this->_merchantGuid) . strtoupper($this->_merchantSecretKey))
        ];

        $response = $this->sendRequest(json_encode($request), 'getMerchatInformation');

        return $response;
    }
    public function changeAccount($merchantSecretKey, $merchantGuid)
    {
        $this->_merchantSecretKey = $merchantSecretKey;
        $this->_merchantGuid = $merchantGuid;
    }

    public function createPayment($request)
    {
        $sum = 0;
        foreach ($request['Products'] as $product) {
            $sum += $product['ProductPrice'] * $product['ProductItemsNum'];
        }

        $request['MerchantGuid'] = $this->_merchantGuid;
        if(!isset($request['Language'])){
            $request['Language'] = strtoupper(\App::getLocale());
        }
        if(!isset($request['Currency'])){
            $request['Currency'] = config('kaznachey.currency');
        }
        if(!isset($request['SelectedPaySystemId'])){
            $request['SelectedPaySystemId'] = config('kaznachey.payment_system');
        }
        //Формируем подпись.
        $signature = strtoupper($this->_merchantGuid) .
                number_format($sum, 2, '.', '') . //Общая сумма. Внимание сумма должна быть в формать 123.23 Дробная часть отделяется точкой и не иметь лишних нулей!
                $request['SelectedPaySystemId'] . //Идентификатор выбранной платежной системы.
                $request['PaymentDetails']['EMail'] . //E-mail покупателя
                $request['PaymentDetails']['PhoneNumber'] . //Телефон покупателя
                $request['PaymentDetails']['MerchantInternalUserId'] . //Идентификатор пользователея в системе мерчента. Исспользуется для анализа в системе Казначей
                $request['PaymentDetails']['MerchantInternalPaymentId'] . //Идентификатор платежа в системе мерчента. Исспользуется для анализа в системе Казначей
                strtoupper($request['Language']) . //Язык страницы оплаты
                strtoupper($request['Currency']) . //Валюта платежа
                strtoupper($this->_merchantSecretKey); //Секретный ключ мерчанта
        $request['Signature'] = md5($signature);

        $response = $this->sendRequest(json_encode($request), 'CreatePaymentEx');

        return $response;
    }

    public function getStatusResponse()
    {
        $request_json = file_get_contents('php://input');
        $request = json_decode($request_json, true);

        $request_sign = $request['ErrorCode'] .
            $request['OrderId'] .
            $request['MerchantInternalPaymentId'] . //MerchantInternalPaymentId
            $request['MerchantInternalUserId'] . //MerchantInternalUserId
            number_format($request['OrderSum'], 2, '.', '') . //Общаяя сумма. Внимание сумма должна быть в формать 123.23 Дробная часть отделяется точкой и не иметь лишних нулей!
            number_format($request['Sum'], 2, '.', '') . //Сумма с вычетом процентов
            strtoupper($request['Currency']) . //Валюта
            $request['CustomMerchantInfo'] . //Сумма с вычетом процентов
            strtoupper($this->_merchantSecretKey);
        $request_sign = md5($request_sign);

        if($request['SignatureEx'] == $request_sign) {
            return $request;
        } else {
            throw new \Exception('Invalid signature! Request: ' . $request_json);
        }
    }

    protected function sendRequest($jsonData, $uri)
    {
        $curl = curl_init();
        if (!$curl)
            return false;

        curl_setopt($curl, CURLOPT_URL, $this->_serverUrl . $uri);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array('Expect: ', 'Content-Type: application/json; charset=UTF-8', 'Content-Length: '
                . strlen($jsonData)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
}

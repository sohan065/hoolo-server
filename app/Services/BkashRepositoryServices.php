<?php

namespace App\Services;


use Sms;
use Exception;
use App\Jobs\PurchaseNotification;
use App\Models\BkashToken;
use App\Models\CourseOrder;
use Illuminate\Support\Str;
use App\Models\ProductOrder;
use App\Models\ProductDetails;
use App\Models\ShippingAddress;
use App\Models\TempCourseOrder;
use App\Models\TempProductOrder;
use App\Models\ProductOrderDetail;
use App\Models\ProductOrderPayment;
use Illuminate\Support\Facades\Log;
use App\Repositories\BkashRepositoryInterface;

class BkashRepositoryServices implements BkashRepositoryInterface
{
    protected $_baseUrl, $_baseHeader;

    function __construct()
    {
        $this->_baseUrl = env('BKASH_BASE_URL');
        $this->_baseHeader = array(
            'Content-Type:application/json',
            'username:' . env('BKASH_CHECKOUT_URL_USER_NAME'),
            'password:' . env('BKASH_CHECKOUT_URL_PASSWORD')
        );
    }

    protected function _authHeaders($token)
    {
        return array(
            'Content-Type:application/json',
            'authorization:' . $token,
            'x-app-key:' . env('BKASH_CHECKOUT_URL_APP_KEY')
        );
    }

    protected function _curlWithBody($url, $header, $method, $body_data)
    {
        $curl = curl_init($this->_baseUrl . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body_data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    protected function _token()
    {
        $body = array(
            'app_key' => env('BKASH_CHECKOUT_URL_APP_KEY'),
            'app_secret' => env('BKASH_CHECKOUT_URL_APP_SECRET'),
        );
        $body_json = json_encode($body);
        $response = $this->_curlWithBody('checkout/token/grant', $this->_baseHeader, 'POST', $body_json);
        $token = json_decode($response)->id_token;
        return $token;
    }
   //  course payment create
    public function coursePaymentCreate($amount, $orderCode)
    {
        $token = $this->_token();

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $token,
            'X-APP-Key:' . env('BKASH_CHECKOUT_URL_APP_KEY')
        );

        $body = array(
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $orderCode
        );
        $body_json = json_encode($body);

        $response = $this->_curlWithBody('checkout/payment/create', $header, 'POST', $body_json);
        $paymentId = json_decode($response)->paymentID;

        $result = BkashToken::create([
            'payment_id' => $paymentId,
            'token' => $token,
        ]);
        $detail = TempCourseOrder::where('order_code', $orderCode)->update(['payment_id' => $paymentId]);
        return response(['response' => json_decode($response), 'token' => $token], 200);
    }
    //  course payment execute
    public function coursePaymentExecute($paymentId)
    {
        $token = BkashToken::where('payment_id', $paymentId)->first()->token;
        $header = $this->_authHeaders($token);

        $body = array(
            'paymentID' => $paymentId
        );
        $body_json = json_encode($body);
        $response = $this->_curlWithBody('checkout/payment/execute/' . $paymentId, $header, 'POST', $body_json);
        $response = json_decode($response);
        $token_delete = BkashToken::where('payment_id', $paymentId)->delete();
   if (property_exists($response, 'transactionStatus')) {
            $trx_number = $response->customerMsisdn;
            $trx_id = $response->trxID;
            $payment_id = $paymentId;
            $orderCode = $response->merchantInvoiceNumber;
            $amount = $response->amount;
            $temp = TempCourseOrder::where('order_code', $orderCode)->first();
            try {
                $orderStore = CourseOrder::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $temp->user_uuid,
                    'course_uuid' => $temp->course_uuid,
                    'payment_method' => 'Bkash',
                    'order_code' =>  $orderCode,
                    'price' => $temp->price,
                    'trx_number' => $trx_number,
                    'trx_id' => $trx_id,
                    'payment_id' => $payment_id,
                ]);
            } catch (Exception $e) {
                Log::error($e);
                $OrderStore = false;
            }
            if ($orderStore) {
                // admin notification
                $adminPhone = env('ADMIN_PHONE_NOTIFICATION');
                $msg = 'New course order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($adminPhone, $msg));
                // user notification
                $userPhone = '88' . $trx_number;
                $msg = 'Your order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($userPhone, $msg));
                TempCourseOrder::where('order_code', $orderCode)->delete();
                return response(['message' => 'order placed successfully', 'response' => $response], 201);
            } else {
                $response = $this->refund($orderCode, $trx_number, $payment_id, $amount);
                if (property_exists($response, 'transactionStatus') && property_exists($response, 'refundTrxID')) {
                    TempCourseOrder::where('order_code', $orderCode)->update([
                        'refund_ref_id' => $response->refundTrxID
                    ]);
                }
            }
        }
       else if (property_exists($response, 'errorCode') && $response->errorCode  == "2023") {
            return response(['message' => 'Insufficient balance'], 402);
        }
        else if (property_exists($response, 'errorCode') && $response->errorCode  == "2029") {
            return response(['message' => 'Duplicate for all transaction'], 429);
        }
        return response(['message' => 'Server Error'], 406);
    }
    //  course payment cancel
    public function coursePaymentCancel($paymentId)
    {
        TempCourseOrder::where('payment_id', $paymentId)->delete();
        return response(['message' => 'Order has been cancelled'], 200);
    }
    //  product payment  create
    public function productPaymentCreate($amount, $orderCode)
    {
        $token = $this->_token();

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $token,
            'X-APP-Key:' . env('BKASH_CHECKOUT_URL_APP_KEY')
        );

        $body = array(
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $orderCode
        );
        $body_json = json_encode($body);

        $response = $this->_curlWithBody('checkout/payment/create', $header, 'POST', $body_json);
        $paymentId = json_decode($response)->paymentID;

        $result = BkashToken::create([
            'payment_id' => $paymentId,
            'token' => $token,
        ]);
        $detail = TempProductOrder::where('order_code', $orderCode)->update(['payment_id' => $paymentId]);

        return response(['response' => json_decode($response), 'token' => $token]);
    }
    // product payment execute
    public function productPaymentExecute($paymentId)
    {
        $token = BkashToken::where('payment_id', $paymentId)->first()->token;
        $header = $this->_authHeaders($token);

        $body = array(
            'paymentID' => $paymentId
        );
        $body_json = json_encode($body);
        $response = $this->_curlWithBody('checkout/payment/execute/' . $paymentId, $header, 'POST', $body_json);
        $response = json_decode($response);
        $token_delete = BkashToken::where('payment_id', $paymentId)->delete();

        if (property_exists($response, 'transactionStatus')) {
            $trx_number = $response->customerMsisdn;
            $trx_id = $response->trxID;
            $payment_id = $paymentId;
             $orderCode = $response->merchantInvoiceNumber;
            $amount = $response->amount;
            $temp = TempProductOrder::where('order_code', $orderCode)->get();

            foreach ($temp as $temp) {
                try {
                    $orderStore = ProductOrder::create([
                        'uuid' => Str::uuid(),
                        'user_uuid' => $temp->user_uuid,
                        'product_uuid' => $temp->product_uuid,
                        'merchant_uuid' => $temp->merchant_uuid,
                        'price' => $temp->price,
                        'quantity' =>  $temp->quantity,
                        'order_code' =>   $orderCode,
                    ]);
                    $productInfo = ProductDetails::where('product_uuid', $temp->product_uuid)->first();
                    $remainStock = $productInfo['stock'] - $temp->quantity;
                    ProductDetails::where('product_uuid', $temp->product_uuid)->update([
                        'stock' => $remainStock,
                    ]);
                } catch (Exception $e) {
                    Log::error($e);
                  
                    $OrderStore = false;
                }
            }
            $addressUuid = TempProductOrder::where('order_code', $orderCode)->first()->address_uuid;
            $address = ShippingAddress::where('uuid', $addressUuid)->first();
            try {
                $details =  ProductOrderDetail::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $temp->user_uuid,
                    'order_code' =>  $orderCode,
                    'address' => $address->address,
                    'post_code' => $address->post_code_uuid,
                    'thana' => $address->thana_uuid,
                    'city' => $address->city_uuid,
                    'state' => $address->state_uuid,
                    'country' => $address->country_uuid,
                    'phone' =>  $address->phone,
                    'name' =>  $address->name,
                    'order_status'=>1,
                    'shipping_cost' => 0,
                ]);
            } catch (Exception $e) {
                log::error($e);
                $details = false;
            }
            try {
                $payment =  ProductOrderPayment::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $temp->user_uuid,
                    'order_code' => $orderCode,
                    'payment_method' => 'Bkash',
                    'trx_number' => $trx_number,
                    'trx_id' =>  $trx_id,
                    'payment_id' =>   $payment_id,
                    'status'=>1,
                    'payment_with' => null,
                ]);
            } catch (Exception $e) {
                log::error($e);
              
                $payment = false;
            }

            if ($orderStore && $details && $payment) {
                // admin notification
                $adminPhone = env('ADMIN_PHONE_NOTIFICATION');
                $msg = 'New product order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($adminPhone, $msg));
                // user notification
                $userPhone = '88' . $trx_number;
                $msg = 'Your product order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($userPhone, $msg));
               
                TempProductOrder::where('order_code', $orderCode)->delete();
                return response(['message' => 'order placed successfully', 'response' => $response], 201);
            } else {
                ProductOrder::where('order_code', $orderCode)->delete();
                ProductOrderDetail::where('order_code', $orderCode)->delete();
                ProductOrderPayment::where('order_code', $orderCode)->delete();
                $response = $this->refund($orderCode, $trx_number, $payment_id, $amount);
                if (property_exists($response, 'transactionStatus') && property_exists($response, 'refundTrxID')) {
                    TempProductOrder::where('order_code', $orderCode)->update([
                        'refund_ref_id' => $response->refundTrxID
                    ]);
                }
            }
        } else if (property_exists($response, 'errorCode') && $response->errorCode  == "2023") {
            return response(['message' => 'Insufficient balance'], 402);
        } else if (property_exists($response, 'errorCode') && $response->errorCode  == "2029") {
            return response(['message' => 'Duplicate for all transaction'], 429);
        }
        return response(['message' => 'not acceptable'], 406);
    }

    public function productPaymentCancel($paymentId)
    {
        TempProductOrder::where('payment_id', $paymentId)->delete();
        return response(['message' => 'Order has been cancelled'], 200);
    }
    // refund
    public function refund($orderCode, $trxNumber, $paymentId, $amount)
    {
        $token  = $this->_token();
        $header = $this->_authHeaders($token);
        $reason = 'user wanted to cancel order';
        $amount = $amount;

        $body = array(
            'paymentID' => $paymentId,
            'amount' => $amount,
            'trxID' => $trxNumber,
            'sku' => $orderCode,
            'reason' => $reason
        );

        $body_json = json_encode($body);

        $response = $this->_curlWithBody('checkout/payment/refund', $header, 'POST', $body_json);
        return json_decode($response);
    }

    protected function deleteTempCourseOrder($paymentId)
    {
        return TempCourseOrder::where('payment_id', $paymentId)->delete();
    }
}

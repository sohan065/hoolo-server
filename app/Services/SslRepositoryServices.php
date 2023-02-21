<?php

namespace App\Services;

use Sms;
use Token;
use Exception;
use App\Models\User;
use App\Jobs\PurchaseNotification;
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
use App\Repositories\SslRepositoryInterface;

class SslRepositoryServices implements SslRepositoryInterface
{
    // course order payment by CARD
    public function courseOrderByCard($token, $totalPrice, $orderCode)
    {
        $tokenInfo = Token::decode($token);
        $user = User::where('uuid', $tokenInfo['uuid'])->with('userInfo')->first();
        $post_data = array();

        # BASIC CREDENTIALS

        $post_data['store_id'] = env('SSL_STORE_ID');

        $post_data['store_passwd'] = env('SSL_STORE_PASSWORD');

        $post_data['currency'] = "BDT";

        $post_data['tran_id'] = $orderCode;

        $post_data['success_url'] = env('APP_URL')."/api/course/card/success";

        $post_data['fail_url'] = env('APP_URL')."/api/course/card/fail";

        $post_data['cancel_url'] = env('APP_URL')."/api/course/card/cancel";
        // $post_data['product_profile'] = "";
        $post_data['multi_card_name'] = "mastercard,visacard";  # DISABLE TO DISPLAY ALL AVAILABLE

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->userInfo ? $user->userInfo->user_name : '' ;
        $post_data['cus_email'] = $user->email ? $user->email : '';
        $post_data['cus_add1'] = '';
        $post_data['cus_city'] = '';
        $post_data['cus_postcode']  = '';
        $post_data['cus_country'] = '';
        $post_data['cus_phone'] = $user->phone;
        # PAYMENT AMOUNT 
        $post_data['total_amount'] = $totalPrice;
        # REQUEST SEND TO SSLCOMMERZ

        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";

        # CURL

        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $direct_api_url);

        curl_setopt($handle, CURLOPT_TIMEOUT, 30);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($handle, CURLOPT_POST, 1);

        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {

            curl_close($handle);

            $sslcommerzResponse = $content;
        } else {

            curl_close($handle);

            return response('failed', 500);
        }

        # PARSE THE JSON RESPONSE

        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {

            $data = ['order_code' => $orderCode, 'gateway' => $sslcz['GatewayPageURL']];
            return response($data, 201);
        } else {
            return response(['message' => 'not acceptable'], 406);
        }
    }
    // course order CARD payment success 
    public function courseCardSuccess($credentials)
    {
        $orderCode = $credentials['tran_id'];
        $cardType = $credentials['card_type'];
        $cardNo = $credentials['card_no'];
        $bankTranId = $credentials['bank_tran_id'];

        $temp = TempCourseOrder::where('order_code', $orderCode)->with('user')->first();
        $phone = $temp->user->phone;
        if ($temp) {
            try {
                CourseOrder::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $temp->user_uuid,
                    'course_uuid' => $temp->course_uuid,
                    'price' => $temp->price,
                    'order_code' =>   $orderCode,
                    'payment_method' => 'Card',
                    'card_type' => $cardType,
                    'card_no' => $cardNo,
                    'bank_tran_id' => $bankTranId,
                ]);
                TempCourseOrder::where('order_code', $orderCode)->delete();
                // admin notification
                $adminPhone = env('ADMIN_PHONE_NOTIFICATION');
                $msg = 'New course order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($adminPhone, $msg));
                // user notification
                $msg = 'Your order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($phone, $msg));
                return response(['message' => 'order placed successfully'], 201);
            } catch (Exception $e) {
                Log::error($e);
                $refund = $this->productCardRefund($credentials, 'data store fail');
                if ($refund) {
                    TempCourseOrder::where('order_code', $orderCode)->update([
                        'bank_tran_id' => $refund->bank_tran_id,
                        'refund_ref_id' => $refund->refund_ref_id,
                    ]);
                }
                return response(['message' => 'not acceptable'], 406);
            }
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // product order CARD payment fail 
    function courseCardFail($credentials)
    {
        $orderCode = $credentials['tran_id'];
        TempCourseOrder::where('order_code', $orderCode)->delete();
        return response(['message' => 'order process failed'], 200);
    }
    // product order CARD payment cancel 
    function courseCardCancel($credentials)
    {
        $orderCode = $credentials['tran_id'];
        TempCourseOrder::where('order_code', $orderCode)->delete();
        return response(['message' => 'order cancelled'], 200);
    }
    // product order payment by CARD
    public function productOrderByCard($credentials, $totalPrice, $orderCode)
    {
        $user = ShippingAddress::where('uuid', $credentials['address_uuid'])->with('country', 'state', 'city', 'thana', 'postCode', 'userInfo')->first();
        $post_data = array();

        # BASIC CREDENTIALS

        $post_data['store_id'] = env('SSL_STORE_ID');

        $post_data['store_passwd'] = env('SSL_STORE_PASSWORD');

        $post_data['currency'] = "BDT";

        $post_data['tran_id'] = $orderCode;

        $post_data['success_url'] = env('APP_URL')."/api/product/card/success";

        $post_data['fail_url'] = env('APP_URL')."/api/product/card/fail";

        $post_data['cancel_url'] = env('APP_URL')."/api/product/card/cancel";
        // $post_data['product_profile'] = "";
        $post_data['multi_card_name'] = "mastercard,visacard";  # DISABLE TO DISPLAY ALL AVAILABLE

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name ? $user->name:'';
        $post_data['cus_email'] = $user->userInfo->email ? $user->userInfo->email : '';
        $post_data['cus_add1'] = $user->address;
        $post_data['cus_city'] = $user->city->name;
        $post_data['cus_postcode'] = $user->postCode->name;
        $post_data['cus_country'] = $user->postCode->name;
        $post_data['cus_phone'] = $user->phone;
        # PAYMENT AMOUNT 
        $post_data['total_amount'] = $totalPrice;
        # REQUEST SEND TO SSLCOMMERZ

        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";

        # CURL

        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $direct_api_url);

        curl_setopt($handle, CURLOPT_TIMEOUT, 30);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($handle, CURLOPT_POST, 1);

        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, TRUE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {

            curl_close($handle);

            $sslcommerzResponse = $content;
        } else {

            curl_close($handle);

            return response('failed', 500);
        }

        # PARSE THE JSON RESPONSE

        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {

            $data = ['order_code' => $orderCode, 'gateway' => $sslcz['GatewayPageURL']];
            return response($data, 201);
        } else {
            return response(['message' => 'not acceptable'], 406);
        }
    }
    // product order CARD payment success 
    public function productCardSuccess($credentials)
    {
        $orderCode = $credentials['tran_id'];
        $cardType = $credentials['card_type'];
        $cardNo = $credentials['card_no'];
        $bankTranId = $credentials['bank_tran_id'];

        $temp = TempProductOrder::where('order_code', $orderCode)->get();
        if (count($temp) > 0) {
            try {
                foreach ($temp as $temp) {
                    ProductOrder::create([
                        'uuid' => Str::uuid(),
                        'user_uuid' => $temp->user_uuid,
                        'product_uuid' => $temp->product_uuid,
                        'merchant_uuid' => $temp->merchant_uuid,
                        'price' => $temp->price,
                        'quantity' =>  $temp->quantity,
                        'order_code' =>   $orderCode,
                    ]);
                    $product = ProductDetails::where('product_uuid', $temp->product_uuid)->first();
                    ProductDetails::where('product_uuid', $temp->product_uuid)->update(['stock' => $product->stock - $temp->quantity]);
                }
                $addressUuid = TempProductOrder::where('order_code', $orderCode)->first()->address_uuid;
                $address = ShippingAddress::where('uuid', $addressUuid)->first();
                $temp = TempProductOrder::where('order_code', $orderCode)->first();
                //store order details
                ProductOrderDetail::create([
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
                    'shipping_cost' => 0,
                    'order_status'=>1,
                ]);
                //store order payment
                ProductOrderPayment::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $temp->user_uuid,
                    'order_code' => $orderCode,
                    'payment_method' => 'Card',
                    'card_type' => $cardType,
                    'card_no' => $cardNo,
                    'bank_tran_id' => $bankTranId,
                    'status'=>1,
                ]);

                TempProductOrder::where('order_code', $orderCode)->delete();
                // admin notification
                $adminPhone = env('ADMIN_PHONE_NOTIFICATION');
                $msg = 'New product order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($adminPhone, $msg));
                // user notification
                $userPhone =  $address->phone;
                $msg = 'Your product order placed successfully.Order code: '.$orderCode;
                dispatch(new PurchaseNotification($userPhone, $msg));
                return response(['message' => 'order placed successfully'], 201);
            } catch (Exception $e) {
                Log::error($e);
                $refund = $this->productCardRefund($credentials, 'data store fail');
                if ($refund) {
                    TempProductOrder::where('order_code', $orderCode)->update([
                        'bank_tran_id' => $refund->bank_tran_id,
                        'refund_ref_id' => $refund->refund_ref_id,
                    ]);
                }
                return response(['message' => 'not acceptable'], 406);
            }
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // product order CARD payment fail 
    function productCardFail($credentials)
    {
        $orderCode = $credentials['tran_id'];
        TempProductOrder::where('order_code', $orderCode)->delete();
        return response(['message' => 'order process failed'], 200);
    }
    // product order CARD payment cancel 
    function productCardCancel($credentials)
    {
        $orderCode = $credentials['tran_id'];
        TempProductOrder::where('order_code', $orderCode)->delete();
        return response(['message' => 'order cancelled'], 200);
    }
    // product order CARD payment REFUND 
    public function productCardRefund($credentials, $reasson)
    {
        // return $credentials;
        $bank_tran_id = urlencode($credentials['bank_tran_id']);
        $refund_amount = urlencode($credentials['amount']);
        $refund_remarks = urlencode($reasson);
        $store_id = urlencode(env('SSL_STORE_ID'));
        $store_passwd = urlencode(env('SSL_STORE_PASSWORD'));

        $requested_url = ("https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php?refund_amount=$refund_amount&refund_remarks=$refund_remarks&bank_tran_id=$bank_tran_id&store_id=$store_id&store_passwd=$store_passwd&v=1&format=json");

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

        $result = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($code == 200 && !(curl_errno($handle))) {
            return json_decode($result);
        }
        return false;
    }
}

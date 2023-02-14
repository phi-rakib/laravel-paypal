<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Redirect;


class PaypalController extends Controller
{
    private $client;

    public function __construct()
    {
        $clientId = env('PAYPAL_CLIENT_ID');    
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($environment);
    }

    public function checkout()
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');


        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => rand(000000,999999),
                "amount" => [
                    "value" => "13.00",
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "cancel_url" => url('api/paypal/payment/cancel'),
                "return_url" => url('api/paypal/payment/done')
            ] 
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return Redirect::to($response->result->links[1]->href);
        }catch (HttpException $ex) {

        }
    }

    public function getCancel(Request $request)
    {
    	return redirect()->route('home');
    }

    public function getDone(Request $request)
    {
        $ordersCaptureRequest = new OrdersCaptureRequest($request->token);
        $ordersCaptureRequest->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($ordersCaptureRequest);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return json_encode($response);
        }catch (HttpException $ex) {

        }
    }
}

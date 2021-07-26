<?php


namespace Netmarket\Stripe\Services;


use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe;
use Session;


class StripeService
{



    private $_sepatDataWithSecretKey;
    private $_cardDataWithSecretKey;
    /**
     * @var array
     */
    private $__giroDataWithSecretKey;
    /**
     * @var array
     */
    private $_sofortDataWithSecretKey;


    public function __construct()
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    }





    /******************************************************************************************************************|
    |******************************************************************************************************************|
    |******************************************************************************************************************|
    |************************************** -> Stripe API  <-**********************************************************|
    |******************************************************************************************************************|
    |******************************************************************************************************************|
    |******************************************************************************************************************|
     */


    /**
     * create the card payment and generate a secret key for the client authentication
     * @param $request_data
     * @param $amount
     * @param $total_cost
     * @return bool
     */
    public function cardPaymentIntent($request_data,$amount, $total_cost):bool
    {
        // dd($request_data);
        // create the payment , if the credit card service provider used two factor authentication , then the user will be redirected to 3D secure page where the user must provide
        // push TAN etc then the payment will be processed with status success or failed. if the the credit card service provider does not authentication on the client side then the payment
        // will direclty take place with status either successful or failed

        try {
            $intent = PaymentIntent::create([
                'amount' => ($amount) * 100,
                'currency' => 'EUR',
                'metadata' => [
                    'integration_check' => 'accept_a_payment',
//                    'order_id/event_id' =>  session()->get('promoter_event_ID'),
//                    'name' => $name,
//                    'email'=> $email,
                    'summe' => $total_cost,
//                    'summe_mit_provision' => $cost_with_provision
                ],
            ]);



            // place the order
            // $order = new OrderController($this->Database);
            //  $order->saveEventInvoiceAddress($request_data);

        } catch (Stripe\Exception\ApiErrorException $e)
        {

            echo $e;
            //return view('profil.error')->with('Ausfall', $e);
            return false;

        } catch (ApiErrorException $e) {
        }

        // dd($intent);

        // get the client secret and some user information
        $data = array(
//            'name'=> $name,
//            'email'=>$email,
            'amount'=>$amount,
            'client_secret'=>$intent->client_secret,
        );

        $this->_cardDataWithSecretKey = $data;


        return true;

    }


    /**
     * return the name, email , amount and client secret key
     * @return mixed
     *
     */
    public function getCardDataWithSecretKey()
    {
        return $this->_cardDataWithSecretKey;
    }


    /**
     * create the giro payment and generate a secret key for the client authentication
     * @param $request_data
     * @param $name
     * @param $email
     * @param $amount
     * @param $payment_method
     * @return bool
     */
    public function giroPaymentIntent($request_data,$amount, $payment_method):bool
    {

        // create the payment , if the credit card service provider used two factor authentication , then the user will be redirected to 3D secure page where the user must provide
        // push TAN etc then the payment will be processed with status success or failed. if the the credit card service provider does not authentication on the client side then the payment
        // will direclty take place with status either successful or failed

        try {
            $intent = PaymentIntent::create([
                'amount' => ($amount) * 100,
                'currency' => 'EUR',
                'payment_method_types' => [ $payment_method],

            ]);

            // place the order
            // $order = new OrderController($this->Database);
            // $order->saveEventInvoiceAddress($request_data);

        } catch (Stripe\Exception\ApiErrorException $e)
        {

//        return view('profil.error')->with('Ausfall', $e);
            return false;

        }

        // get the client secret and some user information
        $data = array(
//            'name'=>$name,
//            'email'=>$email,
            'amount'=>$amount,
            'client_secret'=>$intent->client_secret,
        );

        $this->__giroDataWithSecretKey = $data;



        return true;

    }


    /**
     * return the name, email , amount and client secret key
     * @return array
     *
     */
    public function getGiroDataWithSecretKey()
    {
        return $this->__giroDataWithSecretKey;
    }

    /**
     * create the sofort payment and generate a secret key for the client authentication
     * @param $request_data
     * @param $name
     * @param $email
     * @param $amount
     * @param $payment_method
     * @return bool
     */
    public function sofortPaymentIntent($request_data, $name, $email, $amount, $payment_method):bool
    {

        // save bank details during a sofort banking
        // $customer = \Stripe\Customer::create();
        // create the payment , if the credit card service provider used two factor authentication , then the user will be redirected to 3D secure page where the user must provide
        // push TAN etc then the payment will be processed with status success or failed. if the the credit card service provider does not authentication on the client side then the payment
        // will direclty take place with status either successful or failed

        try {

            $intent = PaymentIntent::create([
                'amount' => ($amount) * 100,
                'currency' => 'EUR',
                'payment_method_types' => [$payment_method],
//              'customer' => $customer->id,
//              'setup_future_usage' => 'off_session',
                'payment_method_options' => [
                    'sofort' => [
                        'preferred_language' => 'de',
                    ],
                ],
            ]);

            // place the order
            //   $order = new OrderController($this->Database);
            // $order->saveEventInvoiceAddress($request_data);

        } catch (Stripe\Exception\ApiErrorException $e)
        {
            // return view('profil.error')->with('Ausfall', $e);
            return false;
        }

        // get the client secret and some user information
        $data = array(
            'name'=>$name,
            'email'=>$email,
            'amount'=>$amount,
            'client_secret'=>$intent->client_secret,
        );

        $this->_sofortDataWithSecretKey = $data;


        return true;

    }


    /**
     * return the name, email , amount and client secret key
     * @return array
     *
     */
    public function getSofortDataWithSecretKey()
    {
        return $this->_sofortDataWithSecretKey;
    }



    public function sepaPaymentIntent($request_data, $name, $email,$amount, $payment_method):bool
    {

        try {
            $customer = Customer::create();

            $intent = PaymentIntent::create([
                'amount' => ($amount) * 100,
                'currency' => 'EUR',
                'payment_method_types' => [$payment_method],
                'setup_future_usage' => 'off_session',
                'customer' => $customer->id,
                'metadata' => ['integration_check' => 'sepa_debit_accept_a_payment'],
            ]);

            // place the order
            //  $order = new OrderController($this->Database);
            // $order->saveEventInvoiceAddress($request_data);

        } catch (Stripe\Exception\ApiErrorException $e)
        {
            // return view('profil.error')->with('Ausfall', $e);
            return false;
        }

        // get the client secret and some user information
        $data = array(
            'name'=>$name,
            'email'=>$email,
            'amount'=>$amount,
            'client_secret'=>$intent->client_secret,
        );

        $this->_sepatDataWithSecretKey = $data;


        return true;
    }

    public function getSepaDataWithSecretKey()
    {
        return $this->_sepatDataWithSecretKey;
    }

}

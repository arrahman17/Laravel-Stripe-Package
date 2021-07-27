<?php
namespace Netmarket\Stripe\Http\Controllers;





use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Netmarket\Stripe\Services\StripeService;


class PaymentController extends Controller
{



    private $Stripe;

    public function __construct(StripeService $stripeService)
    {

        // stripe service( api ) is injected here
        $this->Stripe = $stripeService;

    }


    /**
     * Stripe main method for processing the payment
     * @param Request $request_data
     * @return Factory|View|RedirectResponse|Redirector
     */
    public function paymentProcessing(Request $request_data)
    {


       //  dd($request_data);
        // clear the session of secret key
        session()->forget('data');

        session()->put('amount', $request_data->get('cost_with_provision'));
        session()->put('payment_method', $request_data->get('payment_method'));

        $name = $request_data->get('name'); //  card holder name
        session()->put('payer_name' ,$name);
        $email = $request_data->get('email'); // card holder email
        session()->put('payer_email', $email);
        $amount = $request_data->get('cost_with_provision'); // amount to pay
        $payment_method = $request_data->get('payment_method'); // payment method
        $total_cost =  $request_data->get('total_cost'); // total cost
        $cost_with_proviosn = $request_data->get('cost_with_provision'); // cost with provision


        if($payment_method === 'creditCard')
        {
          //  dd($this->Stripe->cardPaymentIntent($request_data, $amount, $total_cost));
            if($this->Stripe->cardPaymentIntent($request_data, $amount, $total_cost))
            {
                $data = $this->Stripe->getCardDataWithSecretKey();
                //dd($data);
               // return redirect('card-payment')->with( ["data"=>$data]);
                return view('stripe::card_payment',  ["data"=>$data]);
            }
            else {
                return redirect('/closed');
            }
        }
        elseif ( $payment_method=== 'giropay')
        {
            if($this->Stripe->giroPaymentIntent($request_data, $amount, $payment_method))
            {
                $data = $this->Stripe->getGiroDataWithSecretKey();
                return view('stripe::giro_payment',  ["data"=>$data]);
            }
            else {
                return redirect('/closed');
            }

        }
        elseif ($payment_method === 'sofort')
        {
            if($this->Stripe->sofortPaymentIntent($request_data, $name, $email,$amount, $payment_method))
            {
                $data = $this->Stripe->getSofortDataWithSecretKey();
                // dd($data);
                return view('stripe::sofort_payment', ["data"=>$data]);
            }
            else {
                return redirect('/closed');
            }

        }
        elseif ($payment_method === 'sepa_debit')
        {
            if($this->Stripe->sepaPaymentIntent($request_data, $name, $email, $amount, $payment_method))
            {
                $data = $this->Stripe->getSepaDataWithSecretKey();
                // dd($data);
                return view('stripe::sepa_payment', ["data"=>$data]);
            }
            else {
                return redirect('/closed');
            }

        }
        else if($payment_method === 'paypal')
        {
            $event_id =  session()->get('promoter_event_ID');

            return redirect('/paypal/checkout/'.$event_id.'/'.$amount);

        }
        else {
            return redirect('/closed');
        }

    }


    /**
     * on  success , save the order and send an email to the responsible person
     */
    public function paymentSuccess()
    {

        // place the order
      //  $order = new OrderController($this->Database);
        // $order->saveOrder();

        $payment_method = session()->get('payment_method');
        $data = array(
            'Event' =>  session()->get('promoter_event_ID'),
            'Betrag' => session()->get('amount'),
            'Zahlungsmethode'=> $payment_method,
            'Zahlungsstatus ' => 'Bezahlt',
            'Name' => session()->get('payer_name'),
            'Email' => session()->get('payer_email'),
        );

        // send mail to the registered user with login credentials
//        Mail::to(session('Email'))->send(new StripePaymentSuccessfullMail($data));

        return view('stripe::success')->with('Success', 'Zahlung erfolgreich!');

    }


}

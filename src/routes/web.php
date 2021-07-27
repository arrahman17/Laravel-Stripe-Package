
<?php



use Illuminate\Support\Facades\Route;
use Netmarket\Stripe\Http\Controllers\PaymentController;


Route::get('stripe', function (){

    return view('stripe::stripe');
});

Route::get('card-payment', function (){

    return view('stripe::cardpayment');
});

// redirect to payment processing page to check the status of the sepa payment whether it is successful or not
Route::get('/inbearbeitung/{cleintSecretID}', function (){

    return view('stripe::sepa_payment_bearbeitung');
});

// stripe package routing
Route::group(['namespace' => 'Netmarket\Stripe\Http\Controllers'], function(){
    Route::get('/stripe/payment', [PaymentController::class, 'paymentProcessing'])->name('stripe.checkout');
    Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);
    Route::get('/error', [PaymentController::class, 'error']);



});





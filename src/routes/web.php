
<?php



use Illuminate\Support\Facades\Route;
use Netmarket\Stripe\Http\Controllers\PaymentController;


Route::get('stripe', function (){

    return view('stripe::stripe');
});
Route::get('card-payment', function (){

    return view('stripe::cardpayment');
});


Route::group(['namespace' => 'Netmarket\Stripe\Http\Controllers'], function(){
    Route::get('/stripe/payment', [PaymentController::class, 'paymentProcessing'])->name('stripe.checkout');
    Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);
    Route::get('/error', [PaymentController::class, 'error']);



});





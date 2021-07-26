<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stripe Payment API</title>
</head>
<body>
<span> Hey! you want to pay with Stripe</span>

<form action="/stripe/payment" method="get">
    <div class="col-md-12 ">
        <input type="hidden" name="cost_with_provision" value="5">
        <input type="hidden" name="total_cost" value="4">
        <input type="hidden" name="payment_method" value="creditCard">

        <button type="submit" >Pay Now!</button>

    </div>

</form>



</body>
</html>

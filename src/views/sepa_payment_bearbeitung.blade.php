<!DOCTYPE html>
<html lang="en">
<head>
    <title>Stripe</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="container">
    <div class="jumbotron">

        <div id="card-success" role="alert" style="color: green;font-weight:bolder"></div>

        <div id="card-errors" role="alert" style="color: red;"></div>

        <button class=" btn btn-primary" onclick="getPaymentStatus()">Zahlungsstatus prüfen</button>
    </div>
</div>

<script type="text/javascript">

    let stripe = Stripe('pk_test_51JBJnWDAM6MSpQX4zhtaDCVKCAbZxroxsub6Vy1M63GAxjcN8tmsgrDK06LY0UTTnQVQvr3sQ3X9uvs05Dd3XFlx00jRfH8u2J');

    const url = window.location.href;
    const split = url.split("bearbeitung/");
    let clientSecret = split[1];

    function getPaymentStatus() {
        const displaySuccess = document.getElementById('card-success');
        displaySuccess.textContent = "";

        stripe.retrievePaymentIntent(clientSecret)
            .then(function(response)
            {
                let status = response.paymentIntent.status;
                console.log(response);
                if (response.error)
                {
                    // Handle error
                    const displaySuccess = document.getElementById('card-errors');
                    displaySuccess.textContent = "Fehler Aufgetreten";
                }
                else if (status === 'succeeded')
                {
                    // Handle successful payment
                    const displaySuccess = document.getElementById('card-success');
                    displaySuccess.textContent = "Zahlung erfolgreich durchgeführt.";
                }
                else if (status === 'requires_payment_method')
                {

                    const displaySuccess = document.getElementById('card-errors');
                    displaySuccess.textContent = "Fehler aufgetreten: erfordert_zahlungsmethode";
                }
                else if (status === 'lost')
                {
                    const displaySuccess = document.getElementById('card-errors');
                    displaySuccess.textContent = "Fehler aufgetreten: erfordert_zahlungsmethode";
                }
                else if (status === 'processing')
                {
                    const displaySuccess = document.getElementById('card-success');
                    displaySuccess.textContent = "Die Zahlung ist noch in Bearbeitung, Sie werden informiert, ob sie erfolgreich war oder nicht refresh nach dem 3 minuten.";
                }

            });

    }










</script>
</body>
</html>

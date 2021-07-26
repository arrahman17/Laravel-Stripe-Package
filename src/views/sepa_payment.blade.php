<!DOCTYPE html>
<html lang="en">
<head>
    <title>SCA Stripe</title>
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
        <h2 class="text-center">Bezahlen mit 3D Secure</h2>
        <form id="payment-form">
            {{--            <div class="form-group">--}}
            {{--                <input type="text" value="{{ $data['name'] }}" readonly  class="form-control"  id="name" name="name" required>--}}
            {{--            </div>--}}
            {{--            <div class="form-group">--}}
            {{--                <input type="email" value="{{ $data['email'] }}" readonly class="form-control"  id="email" name="email" required>--}}
            {{--            </div>--}}
            {{--            <div class="form-group">--}}
            {{--                <input type="text" value="&#8364; {{ $data['amount'] }}" readonly  class="form-control"  id="amount" name="amount" required>--}}
            {{--            </div>--}}
            <div class="form-group pt-2">
                <label for="iban-element">
                    IBAN
                </label>
                <div id="iban-element" >
                    <!-- Elements will create input elements here -->
                </div>
                <!-- We'll put the error messages in this element -->
            </div>
            <div class="form-group pt-2">
                <button id="submit" class="btn btn-block btn-success paynow">Jetzt bezahlen</button>
            </div>
            <div id="mandate-acceptance">
                Indem Sie Ihre Zahlungsinformationen bereitstellen und diese Zahlung bestätigen, ermächtigen Sie (A) Rocket Rides und Stripe, unseren Zahlungsdienstleister und/oder PPRO, seinen lokalen Dienstleister
                , Anweisungen an Ihre Bank zu senden, um Ihr Konto zu belasten und (B) Ihre Bank, Ihr Konto gemäß diesen Anweisungen zu belasten. Als Teil Ihrer Rechte haben Sie Anspruch auf eine Rückerstattung
                von Ihrer Bank gemäß den Bedingungen Ihrer Vereinbarung mit Ihrer Bank. Eine Erstattung muss innerhalb von 8 Wochen ab dem Datum der Abbuchung von Ihrem Konto geltend gemacht werden.
                Ihre Rechte werden in einer Erklärung erläutert, die Sie von Ihrer Bank erhalten können. Sie erklären sich damit einverstanden, Benachrichtigungen für zukünftige Abbuchungen bis zu 2 Tage vor deren Eintreten zu erhalten.

            </div>
            <div id="card-errors" role="alert" style="color: red;"></div>
            <div id="card-thank" role="alert" style="color: green;"></div>
            <div id="card-message" role="alert" style="color: green;"></div>
            <div id="card-success" role="alert" style="color: green;font-weight:bolder"></div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $('#card-success').text('');
    $('#card-errors').text('');
    //Remember to change this to your live publishable key in production
    let stripe = Stripe('pk_test_51JBJnWDAM6MSpQX4zhtaDCVKCAbZxroxsub6Vy1M63GAxjcN8tmsgrDK06LY0UTTnQVQvr3sQ3X9uvs05Dd3XFlx00jRfH8u2J');
    let elements = stripe.elements();
    $('#submit').prop('disabled', true);
    // Set up Stripe.js and Elements to use in checkout form

    // Set up Stripe.js and Elements to use in checkout form
    let style = {
        base: {
            color: '#32325d',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            },
            ':-webkit-autofill': {
                color: '#32325d',
            },
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a',
            ':-webkit-autofill': {
                color: '#fa755a',
            },
        },
    };

    let options = {
        style: style,
        supportedCountries: ['SEPA'],
        // Elements can use a placeholder as an example IBAN that reflects
        // the IBAN format of your customer's country. If you know your
        // customer's country, we recommend that you pass it to the Element as the
        // placeholderCountry.
        placeholderCountry: 'DE',
    };

    var iban = elements.create('iban', options);

    // Add an instance of the IBAN Element into the `iban-element` <div>
    iban.mount('#iban-element');

    iban.addEventListener('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
            $('#submit').prop('disabled', true);
        } else {
            displayError.textContent = '';
            $('#submit').prop('disabled', false);
        }
    });

    let form = document.getElementById('payment-form');

    form.addEventListener('submit', function(ev) {
        $('.loading').css('display','block');
        ev.preventDefault();
        //cardnumber,exp-date,cvc
        stripe.confirmSepaDebitPayment('{{ $data["client_secret"] }}', {
            payment_method: {
                sepa_debit: iban,
                billing_details: {
                    name: 'abur',
                    email: 'abdur123@gmail.com'
                }
            },
            setup_future_usage: 'off_session'
        }).then(function(result) {
            $('.loading').css('display','none');
            // return false;
            if (result.error) {
                // Show error to your customer (e.g., insufficient funds)
                $('#card-errors').text(result.error.message);
            } else {
                // The payment has been processed!
                if (result.paymentIntent.status === 'succeeded') {
                    $('#card-success').text("Zahlung erfolgreich mit SCA durchgeführt.");

                    setTimeout(function(){ window.location.href = "{{url('/payment-success')}}"; }, 2000);
                }
                return false;
            }
        });
    });



</script>

</body>
</html>

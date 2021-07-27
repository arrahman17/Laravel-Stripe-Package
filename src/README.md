# Laravel Package for Paypal Payment Integration
# Package info

This package is a gateway to the Stripe Payment API, in order to use this package from the github repo.

**Framework => Laravel 8**


# There are some steps to follow:

 **Update the Stripe key and secret key** 

 - in .env add this along with your Stripe credentials:
 - STRIPE_KEY = 
 - STRIPE_SECRET = 

**add this to composer** 
 
 - in require =>  "Netmarket/Stripe": "^1.0.0" 
 - then add this below require
 - "repositories": [
        {
            "type": "vcs",
            "url": " http://git.netmarket.de/Git.Server/Stripe-Payment-API.git"
        }
    ],


**Add in config**

- config->app.php in  'providers' => [ ......
  Netmarket\Stripe\StripeServiceProvider::class
  ],
  
  
**In Terminal run just** 

- "composer update"

**Route**
- For credit card
- http://localhost:8000/stripe/payment?cost_with_provision=5&total_cost=4&payment_method=creditCard



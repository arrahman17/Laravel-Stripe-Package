# Laravel Package for Paypal Payment Integration
# Package info

This package is a gateway to the Stripe Payment API, in order to use this package from the github repo.

**Framework => Laravel 8 with PHP 7.3**

- I hope Laravel 7 could also be compatible 

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
            "url": "https://github.com/arrahman17/Laravel-Stripe-Package"
        }
    ],


**Add in config**

- config->app.php in  'providers' => [ ......
  Netmarket\Stripe\StripeServiceProvider::class
  ],
  
  
**In Terminal run just** 

- "composer update"



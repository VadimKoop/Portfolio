Paysera for WooCommerce
=======================

Version: 2.6.0

Date: 2021-01-07

Contributors: Paysera

Tags: online payment, payment, payment gateway, SMS payments, international payments, mobile payment, sms pay, payment 
by sms, billing system, payment institution, macro payments, micro payments, sms bank

Requires at least: 4.0

Tested up to: 5.5

Stable tag: 2.6.0

Requires PHP: 5.4

Minimum requirements: WooCommerce 3.0

License: GPLv3

License URL: http://www.gnu.org/licenses/gpl-3.0.html

Description
-----------

Collecting payments with Paysera is simple, fast and secure. It is enough to open Paysera account, install the plug-in 
to your store and you will be able to use all of the most popular ways of payment collection in one place.
No need for complicated programming or integration work. With Paysera you will receive real time notifications 
about successful transactions in your online store, and the money will reach you sooner. No monthly, registration 
or connection fee.

It is simple to use and administer payment collection, and you can monitor movement of funds in your smartphone.
Client services and consultation takes place 7 days a week, from 8:00 till 20:00 (EET)
Payments are made in real time.

Paysera applies the lowest fees on the market, and payments from foreign banks and systems are converted 
at best possible rates.

To use Paysera payment gateway, register at paysera.com and create your project. To apply for Paysera go to this link: 
https://bank.paysera.com/en/registration#/registration. Our extension, your account and support is free. 
Only for selling a transaction fee is charged. For further information regarding the Paysera fees please visit: 
https://www.paysera.com/v2/en-GB/fees. 

== Benefits and possibilities of using Paysera checkout ==

 - Plenty of payment methods. Payments via more than 10 000 local and foreign banks, and with Visa, MasterCard, 
 Maestro cards. Payments via international payments systems, in cash and directly via Paysera.
 - Plenty of countries and currencies. Service your clients from all over the world. Paysera operates in 184 
 countries and supports more than 30 currencies. The payment window is translated to 18 languages. Let your clients 
 pay you conveniently and provide information the language they can understand.
 - Convenient administration. It is easy to manage one or several e-payment projects, as the account can be accessed 
 at any time of the day and from anywhere by logging in to Paysera e-payments system. 
 - Perhaps the lowest price on the market. Save money with Paysera Checkout. Our prices are among the best on the 
 market and we apply very favorable currency exchanges rates when executing transfers from foreign banks and systems.
 - Real-time notifications. Receive information about a successful payment immediately and issue purchases to 
 clients faster. Be informed about a payment error instantly and return money with a click of a button.
 - All income to one account. It is easy to monitor and manage income when you do not have many accounts in 
 different banks. Income is collected to one Paysera account, which makes it even easier to see 
 the detailed statistics and form reports.
 - Safe payments online. Place the special sign "Safe payment online" and let your clients know that it is safe 
 to purchase goods or services on your website, as you are using Paysera solution for safe payments.
 - Leasing services. Provide your clients with the possibility of hire purchase. Along with payment methods, offer 
 leasing services provided by General Financing and MokiLizingas.
 
 Client services and consultation takes place 7 days a week, from 8:00 till 20:00 (EET).


Installation
------------

Follow video tutorial or instructions below.

https://www.youtube.com/watch?v=ojNf_P4gwPQ

-= Installation by FTP =-

1. Download Paysera plugin zip.

2. Connect to server and go to Wordpress base directory.

3. Create New Folder and name it 'Paysera' in:
     /wp-content/plugins

4. Extract files and directories from zip file to newly created 'Paysera' folder.

5. Activate Paysera payment method:
    Plugins -> Installed Plugins -> WooCommerce Payment Gateway - Paysera -> Activate

6. Configure Paysera plugin in:
    WooCommerce -> Settings -> Checkout
    
   Choice Paysera and enter paysera.com project ID code, password and etc.

7. Save changes.


-= Installation from admin panel =-

1. Download Paysera plugin zip.

2. Connect to Wordpress admin panel.

3. Install Paysera plugin to Wordpress:
    Choice Plugins -> Add New -> Upload Plugin -> Choice File -> Choice downloaded zip -> Install Now

4. Activate Paysera payment method:
    Plugins -> Installed Plugins -> WooCommerce Payment Gateway - Paysera -> Activate

5. Configure Paysera plugin in:
    WooCommerce -> Settings -> Checkout
    
   Choice Paysera and enter paysera.com project ID code, password and etc.

6. Save changes.


-= Installation from admin panel (marketplace)=-

1. Connect to Wordpress admin panel.

2. Install Paysera plugin to Wordpress:
    
    2.1. Choice Plugins -> Add New;
    
    2.2. Find "Paysera for WooCommerce";
    
    2.3. Install.
    
4. Activate Paysera payment method:
    Plugins -> Installed Plugins -> WooCommerce Payment Gateway - Paysera -> Activate

5. Configure Paysera plugin in:
    WooCommerce -> Settings -> Checkout
    
   Choice Paysera and enter paysera.com project ID code, password and etc.

6. Save changes.

Changelog
---------
= 2.6.0 =
* Fix - translations fix

= 2.5.9 =
* Fix - payment display fix
* Fix - incorrect error logging
* Update - documentation link change
* Update - change of the method to reduce stock level
* Update - add additional payment parameter usage
* Update - translations

= 2.5.8 =
* Update - WebToPay library

= 2.5.7 =
* Update - readme information update

= 2.5.6 =
* Update - readme information update

= 2.5.5 =
* Fix - mistype in readme
* Fix - incorrect display when order is created by admin

= 2.5.4 =
* Update - changed variables naming
* Update - code improvements

= 2.5.3 =
* Update - WebToPay library
* Update - default order status establish
* Fix - project ID input validator
* Fix - Estonian language

= 2.5.2 =
* Update - URL special chars decode

= 2.5.1 =
* Fix - incorrect stylesheet file url 
* Update - change order of plugin links
* Update - active payment method border color change

= 2.5.0 =
* Fix - compatibility with older PHP versions
* Fix - file accessibility

= 2.4.9 =
* Fix - plugin settings not displaying in new woocommerce
* Update - plugin links
* Update - callback logic improvement
* Update - add plugin description
* Update - improved plugin init 
* Update - added Admin error text

= 2.4.8 =
* Update - readme information update
* Update - links update

= 2.4.7 =
* Update - readme information update

= 2.4.6 =
* Multilanguage fix
* Added languages: LT, LV, RU, PL, ES
* Admin textfield fix

Upgrade Notice
--------------
= 2.6.0 =

Translations fix.

= 2.5.9 =

Bug fix and improvements.

= 2.5.8 =

Updated WebToPay library to the latest.

= 2.5.7 =

Updated readme information.

= 2.5.6 =

Updated readme information.

= 2.5.5 =

Minor updates and bug fixes.

= 2.5.4 =

Minor updates.

= 2.5.3 =

Minor bugs fixes and improvements.

= 2.5.2 =

URL special chars decode.

= 2.5.1 =

URL fixes and minor updates.

= 2.5.0 =

Codes fixes. Recommended to install.

= 2.4.9 =

Plugin settings display error fix and minor updates.

= 2.4.8 =

Readme information and links update.

= 2.4.7 =

Readme information update.

= 2.4.6 =

Multilanguage implementation and other fix.


Support
-------

For any questions, please look for the answers at https://support.paysera.com or contact customer support center 
by email  support@paysera.com or by phone +44 20 80996963 | +370 700 17217.

For technical documentation visit: https://developers.paysera.com/

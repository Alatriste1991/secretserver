# Secret Server API

## Description
This is a Codeigniter 4 based API system that can be used to store and share secrets in MySQL database.
using the random generated URL.


## Dependencies
PHP 7.4+ (recommended 8+)

## Installing
* Recommended to use environment allows to run apache, PHP, MySQL.
* Configure apache server that the domain points to the public folder
* Configure database connection and baseurl

## How to use
*You can add secret via following mode:

curl --location 'http://yourdomain.com/v1/secret' \
--header 'Content-Type: application/json' \
--header 'accept: application/json' \
--form 'secret="Lorem ipsum sit dolor amet"' \
--form 'expireAfterViews="10"' \
--form 'expireAfter="20"

 You can communicate json or xml format, and you can add your secret (string), expiration time in minutes (int) and the maximum number of views (int). After than you get a hash, which you can use to get back your secret.

*If you want to get your secret, you can take it following mode:

curl --location 'http://yourdomain.com/v1/secret/{hash}' \
--header 'Content-Type: application/json' \
--header 'accept: application/json'

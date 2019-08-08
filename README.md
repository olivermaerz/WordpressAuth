# WordpressAuth
SimpleSAMLphp module to use Wordpress as a SAML 2.0 Identity Provider. (requires PHP 5 >= 5.3 or PHP 7)

WordpressAuth is a SimpleSAMLphp authentication module, that allows to use the Wordpress user database as the authentication source. The code was written for MySQL/MariaDB.

## Setup

### 1 Setup Wordpress on Your Webserver

### 2 Install SimpleSAMLphp on Your Webserver

Install SimpleSAMLphp and follow the SimpleSAMLphp instructions to set it up as an identity provider ([SimpleSAMLphp Identity Provider QuickStart](https://simplesamlphp.org/docs/stable/simplesamlphp-idp)) 

### 3 Add WordpressAuth Module

Create new directory `wordpressauth` under the `modules` directory (`simplesaml/modules/wordpressauth`) and copy the files from this repository to it. 

### 4 Configure Authentication Source 

Edit the configuration file for authentication sources `simplesaml/config/authsources.php` and add:

```php

'wpauthinstance' => array(
    'dsn' => 'mysql:host=localhost;port=3306;dbname=<mysql database name>',
    'username' => '<mysql username>',
    'password' => '<mysql password>',
    'userstable' => 'wp_users',
    'usermetatable' => 'wp_usermeta',
    'wordpressauth:WordpressAuth'
 ),
 
```
Replace the placeholders with your MySQL host, username, password and database name. 

### 5 Set Authentication Source in Metadata File

Edit the metadata file for the hosted SAML 2.0 IdP `simplesaml/metadata/saml20-idp-hosted.php`
and set `wpauthinstance` as your authentication source: 

```php

/*
 * Authentication source to use. Must be one that is configured in
 * 'config/authsources.php'.
 */
'auth' => 'wpauthinstance',
 
```

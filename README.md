# WordpressAuth

Module to use SimpleSAMLphp with a MySQL user database.

(PHP 5 >= 5.3, PHP 7)

Add to your /config/authsources.php :

    'wpauthinstance' => array(
        'sqlauth:SQL',
        'dsn' => 'mysql:host=localhost;port=3306;dbname=<mysql database name>',
        'username' => '<mysql username>',
        'password' => '<mysql password>',
        // 'userstable' => 'wp_users',
        'wordpressauth:WordpressAuth'
    ),

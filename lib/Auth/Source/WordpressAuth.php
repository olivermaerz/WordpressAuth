<?php
class sspmod_wordpressauth_Auth_Source_WordpressAuth   extends sspmod_core_Auth_UserPassBase {

    /* The database DSN */
    private $dsn;

    /* The database username & password. */
    private $username;
    private $password;

    /* Table name for users tables (usually wp_users) */
    private $userstable;

    public function __construct($info, $config) {
        parent::__construct($info, $config);

        if (!is_string($config['dsn'])) {
            throw new Exception('Missing or invalid dsn option in config.');
        }
        $this->dsn = $config['dsn'];
        if (!is_string($config['username'])) {
            throw new Exception('Missing or invalid username option in config.');
        }
        $this->username = $config['username'];
        if (!is_string($config['password'])) {
            throw new Exception('Missing or invalid password option in config.');
        }
        $this->password = $config['password'];

        if (!is_string($config['userstable'])) {
            throw new Exception('Missing or invalid userstable option in config.');
        } else {
            // custom table name is configured
            $this->userstable = $config['userstable'];
        }
    }

    protected function login($username, $password) {
        /* Connect to the database. */
        $db = new PDO($this->dsn, $this->username, $this->password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* Ensure that we are operating with UTF-8 encoding.
         * This command is for MySQL. Other databases may need different commands.
         */
        $db->exec("SET NAMES 'utf8'");

        /* With PDO we use prepared statements. This saves us from having to escape
         * the username in the database query.
         */
        //$st = $db->prepare('SELECT username, password_hash, full_name FROM userdb WHERE username=:username');
        $st = $db->prepare('SELECT user_login, user_pass, display_name, user_email FROM '.$this->userstable.' WHERE user_login = :username');

        if (!$st->execute(array('username' => $username))) {
            throw new Exception("Failed to query database for user.");
        }

        /* Retrieve the row from the database. */
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            /* User not found. */
            throw new SimpleSAML_Error_Error('WRONGUSERPASS');
        }

        /* Check the password against Wordpress wp_users tables */
        require_once( '../../../vendor/PasswordHash.php' );
        $hasher = new PasswordHash(8, TRUE);

        if (!$hasher->CheckPassword($password, $row['user_pass'])){
        //if(password_verify($password, $row['user_pass'])){
            /* Invalid password. */
            SimpleSAML\Logger::warning('WordpressAuth: Wrong password for user ' . var_export($username, TRUE) . '.');
            throw new SimpleSAML_Error_Error('WRONGUSERPASS');
        }

        /* Create the attribute array of the user. */
        $attributes = array(
            'uid' => array($username),
            'displayName' => array($row['display_name']),
            'email' => array($row['user_email']),
            'eduPersonAffiliation' => array('member', 'employee'),
        );

        /* Return the attributes. */
        return $attributes;
    }
}

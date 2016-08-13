<?php
class sspmod_wordpress_Auth_Source_WordpressAuth   extends sspmod_core_Auth_UserPassBase {
    protected function login($username, $password) {
        if ($username !== 'theusername' || $password !== 'thepassword') {
            throw new SimpleSAML_Error_Error('WRONGUSERPASS');
        }
        return array(
            'uid' => array('theusername'),
            'displayName' => array('Some Random User'),
            'eduPersonAffiliation' => array('member', 'employee'),
        );
    }
}

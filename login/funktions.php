<?php
if (! defined('PASSWORD_DEFAULT') ) {
     define('PASSWORD_DEFAULT', 1);
}
if (! function_exists('password_hash') ) {
     function create_salt ($l=22, $allowed='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ./') {
        $salt='';
        for ( $i = 0; $i < $l; $i++ ) {
            $salt .= $allowed{rand( 0, strlen($allowed) - 1 )};
        }
        return $salt;
     }
 
     function password_hash ($password, $dummy) {
         return crypt( $password, '$2y$10$' . create_salt() .'$' );
     }
 
     function password_verify ($password, $hash) {
         return ( $hash == crypt($password, $hash) );
     }
 
     function password_needs_rehash ($hash, $dummy) {
        return ('$2y$10$' != substr($hash, 0, 7) );
     }
}
?>
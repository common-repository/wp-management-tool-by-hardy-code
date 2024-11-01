<?php

/*
|--------------------------------------------------------------------------
| Class: Key
|--------------------------------------------------------------------------
|
| This class handles all interactions with this sites Hardy Code API Site Key.
|
*/

class Key {
    
    /**
     * Generates a new key.
     *    
     * @return string
     */
    
    public static function generate() {
        
        $datetime   = time();
        $name       = Info::name();
        $url        = Info::url();
        $version    = Info::version();
        
        $string     = NONCE_SALT . $datetime . $name . $url . $version;
        
        $return     = hash("sha256", $string);
        
        return $return;
        
    }
    
    /**
     * Assigns supplied key as the key for this site.
     *    
     * @input  string $key
     * @return string
     */
    
    public static function assign($key = false) {
        
        if(!$key || !update_option(OPTION_KEY, $key)) {
            return false;
        }
        
        return $key;
        
    }
    
    /**
     * Returns the current key for this site
     *    
     * @return string or false
     */
    
    public static function get() {
        
        $key    = get_option(OPTION_KEY);
        return $key;
        
    }
    
    
}
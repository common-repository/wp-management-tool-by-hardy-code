<?php

/*
|--------------------------------------------------------------------------
| Class: Repo
|--------------------------------------------------------------------------
|
| A repositiory of some of the more commonly and general functions.
|
*/

class Repo {
    
    /**
     * Encodes standard array into a JSON format.
     *    
     * @return string
     */
    
    public static function JSONEncode($array = false) {
        
        if(!$array || !is_array($array)) {
            return false;
        }
        
        $return = json_encode($array);
        return $return;
        
    }
    
}
<?php

/*
|--------------------------------------------------------------------------
| Class: Plugin
|--------------------------------------------------------------------------
|
| This class handles the various housekeeping activities of the plugin.
|
*/

class Plugin {
    
    /**
     * Determines if the plugin is currently activate via the admin
     * plugin interface.
     * 
     * @param  string $plugin
     * @return bool
     */
    
    public static function status() {
        
        $return    = is_plugin_active(PLUGIN_BASENAME);
        return $return;
        
    }
    
    /**
     * Function that is called upon when the plugin is activated by WordPress.
     *    
     * @return null
     */
    
    public static function activate() {
        
        if( !Key::get() ) {
            
            $key = Key::generate();
            Key::assign($key);
            
        }
        
        WPMT_xmlrpc::supported(true);
        API::pluginURL();
        API::start();
        API::remote_status();
        wp_schedule_single_event(time()+1, 'once_off_push_update_hook', array('x'));
        
        
    }
    
    /**
     * Function that is called upon when this plugin is deactivated.
     *    
     * @return null
     */
    
    public static function disable() {
        
        API::stop();

    }
    
    /**
     * Checks if a certain dependancy is avaialble
     *    
     * @param   string  $name
     * @return  bool
     */
    
    public static function dependancy($name = false, $human = false) {
        
        $return = false;
        
        switch($name) {
            case false:
                break;
            case "curl":
                $return = function_exists('curl_version');
                break;
            case "xmlrpc":
                $return = WPMT_xmlrpc::supported(true);
                break;
        }
        
        if($human) {
            $return = ($return) ? "Supported" : "Not Supported";
        }
        
        return $return;

    }
    
}
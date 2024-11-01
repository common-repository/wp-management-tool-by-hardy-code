<?php

/*
|--------------------------------------------------------------------------
| Class: WPMT_xmlrpc
|--------------------------------------------------------------------------
|
| WPMT intergration with build in XML-RPC API.
|
*/

class WPMT_xmlrpc {
    
    public static $class_name  = "WPMT_xmlrpc";
    public static $call_prefix = "wpmt.";
    
    /**
     * Provides array of methods for WPMT's intergration of the XML-RPC API.
     *    
     * @param   string  @methods
     * @return  array
     */
    
    public static function wpmt_methods( $methods ) {
        
        $methods    = array(
            self::$call_prefix."repeater"                  => array(self::$class_name, "wpmt_repeater"), 
            self::$call_prefix."pushUpdate"                => array(self::$class_name, "wpmt_pushUpdate"), 
            self::$call_prefix."stop"                      => array(self::$class_name, "wpmt_stop"), 
            self::$call_prefix."start"                     => array(self::$class_name, "wpmt_start"), 
            self::$call_prefix."remoteStatusNotification"  => array(self::$class_name, "wpmt_remoteStatusNotification"),
        );
        
        return $methods;
        
    }
    
    /**
     * Determines if XML-RPC is enabled in the WordPress install
     *    
     * @param   bool    @set    If set, enables XML-RPC
     * @return  bool
     */
    
    public static function supported($set = false) {
        
        $wp_version = get_bloginfo("version");
        
        if( version_compare($wp_version, "3.5.0") >= 0 ) {
            return true;
        } elseif( get_option('enable_xmlrpc') ) {
            return true;
        }
        
        if($set) {
            update_option("enable_xmlrpc", true);
        }
        
        return false;
        
    }
    
    /**
     * Activates XML-RPC
     *    
     * @return  null
     */
    
    public static function activate() {
        update_option("enable_xmlrpc", true);
    }
    
    /**
     * Method: Test, repeats string as supplied
     *    
     * @param   string  @args
     * @return  string
     */
    
    public static function wpmt_repeater( $args ) {
        
        $arg1   = (string) $args;
        return $arg1;
        
    }
    
    /**
     * Method: pushUpdate, calls API::push_update
     *    
     * @param   string  @args
     * @return  string
     */
    
    public static function wpmt_pushUpdate( $args ) {
        
        $return = API::push_update();
        return self::$call_prefix."wpmt_pushUpdate"." :: completed";
        
    }
    
    /**
     * Method: start, calls API::start
     *    
     * @param   string  @args
     * @return  string
     */
    
    public static function wpmt_start( $args ) {
        
        $return = API::start();
        return self::$call_prefix."wpmt_start"." :: completed";
        
    }
    
    /**
     * Method: stop, calls API::stop
     *    
     * @param   string  @args
     * @return  string
     */
    
    public static function wpmt_stop( $args ) {
        
        $return = API::stop();
        return self::$call_prefix."wpmt_stop"." :: completed";
        
    }
    
    /**
     * Method: remoteStatusNotification, calls API::remote_status_notification
     *    
     * @param   string  @args
     * @return  string
     */
    
    public static function wpmt_remoteStatusNotification( $args ) {
        
        $return = API::remote_status_notification();
        return self::$call_prefix."wpmt_remoteStatusNotification"." :: completed";
        
    }
    
}
<?php

/*
|--------------------------------------------------------------------------
| Class: API
|--------------------------------------------------------------------------
|
| This class performs tasks related to API requests.
|
*/

class API {

    /**
     * Performs HTTP POST call to Hardy Code's API.
     * Data must be in JSON encoded.
     *    
     * @param   JSON    @data
     * @return  bool
     */
    
    public static function call($data) {
        
        $ch = curl_init(API_URL);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, self::curl_ssl_options("verifypeer") );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, self::curl_ssl_options("verifyhost") );                                                                 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data))                                                                       
        );                                                                                                                   
         
        $result = curl_exec($ch);
        
        return $result;
        
    }
    
    /**
     * Depending on CURL_SSL_CHECKS, provides relevant curl ssl options.
     *    
     * @param   bool    @setting
     * @return  bool int
     */
    
    public static function curl_ssl_options($setting) {
        
        switch($setting) {
            case "verifypeer":
                $return     = (CURL_SSL_CHECKS) ? true : false;
                break;
            case "verifyhost":
                $return     = (CURL_SSL_CHECKS) ? 2 : 0;
                break;
            default:
                $return     = 0;
        }
        
        return $return;
        
    }
    
    /**
     * Returns array of information about this WordPress install.
     *    
     * @return array
     */
    
    public static function info() {
        
        $key                = Key::get();
        $siteName           = Info::name();
        $url                = Info::url();
        $version            = Info::version();
        $themeSlug          = Info::theme("template");
        $themeName          = Info::theme("name");
        $themeVersion       = Info::theme("version");
        $themeUpdates       = Info::themeUpdates();
        $plugins            = Info::plugins();
        $pluginsUpdates     = Info::pluginUpdates();
        $diskTotal          = Info::disk("total");
        $diskFree           = Info::disk("free");
        $apiURL             = Info::apiURL();
        
        $JSONArray = array(
            "call"                      => "push_update",
            "call_id"                   => self::call_id(),
            "site_key"                  => $key,
            "site_name"                 => $siteName,
            "url"                       => $url,
            "version"                   => $version,
            "current_theme_slug"        => $themeSlug,
            "current_theme_name"        => $themeName,
            "current_theme_version"     => $themeVersion,
            "theme_updates"             => $themeUpdates,
            "plugins"                   => $plugins,
            "plugin_updates"            => $pluginsUpdates,
            "total_disk_space"          => $diskTotal,
            "free_disk_space"           => $diskFree,
            "api_url"                   => $apiURL,
        );
        
        return $JSONArray;
        
    }
        
    /**
     * Pushes update of the sites information to the Hardy Code server.
     *    
     * @return null
     */
    
    public static function push_update() {
        
        $infoArray      = self::info();
        $JSONencoded    = Repo::JSONEncode($infoArray);
        return self::call($JSONencoded);
        
    }
    
    /**
     * Turns on the local ability for the plugin to communicate with Hardy Code's server.
     *    
     * @return bool
     */
    
    public static function start() {
        
        if(!update_option(OPTION_STATUS, "enabled")) {
            return false;
        }
        
        return true;
        
    }
    
    /**
     * Turns off the local ability for the plugin to communicate with Hardy Code's server.
     *    
     * @return bool
     */
    
    public static function stop() {
        
        if(!update_option(OPTION_STATUS, "disabled")) {
            return false;
        }
        
        return true;
        
    }
    
    /**
     * Returns the local status of the sites ability to communicate to Hardy Code's server.
     *    
     * @return false or string
     */
    
    public static function status() {
        
        $option = get_option(OPTION_STATUS);
        
        if(!$option) {
            return false;
        }
        
        return $option;
        
    }
    
    /**
     * Determines what the local database believes is the current remote status
     * of tracking for this install.
     *    
     * @param   string or bool  $setTo
     * @return  string or bool
     */
    
    public static function remote_status($setTo = "unknown") {
        
        $option = get_option(OPTION_REMOTE_STATUS);
        
        if(!$option) {
            update_option(OPTION_REMOTE_STATUS, $setTo);
            $option = get_option(OPTION_REMOTE_STATUS);
        }
        
        return $option;
        
    }
    
    /**
     * Updates our record of Hardy Code's server status for this site.
     *    
     * @param   string or bool  $status
     * @return  bool
     */
    
    public static function remote_status_set($status) {
        
        if(!update_option(OPTION_REMOTE_STATUS, $status)) {
            return false;
        }
        
        return true;
        
    }
    
    /**
     * Returns the reporting options of the plugin with the Hardy Code's server.
     *    
     * @return array
     */
    
    public static function remote_reporting_options() {
        
        $status     = self::remote_status();
        $key        = "reporting_remote";
        $options    = array();
        
        switch($status) {
            case "enabled":
                $options[]  = array("Disable", "?page=".PAGE_MENU_SLUG."&".$key."=disable");
                break;
            case "disabled":
                $options[]  = array("Enable", "?page=".PAGE_MENU_SLUG."&".$key."=enable");
                break;
        }
        
        return $options;
        
    }
    
    /**
     * Performs call to the Hardy Code API to request install wide reporting request adjustment.
     *    
     * @param   string  $action
     * @return  null
     */
    
    public static function reporting($action = false) {
        
        if(!$action) {
            return null;
        }
        
        $send   = array(
            "call"                      => $action,
            "site_key"                  => Key::get(),
        );

        $JSONencoded    = Repo::JSONEncode($send);
        self::call($JSONencoded);
        
    }
    
    /**
     * Handles an "remote_status_notification" calls sent by the Hardy Code's server.
     *    
     * @return null
     */
    
    public static function remote_status_notification() {
        
        if( isset($_POST["status"]) ) {

            switch($_POST["status"]) {
                case "enabled":
                    self::remote_status_set("enabled");
                    break;
                case "disabled":
                    self::remote_status_set("disabled");
                    break;
            }
            
        }
        
    }
    
    /**
     * Generates an ID string for an indidual call
     *    
     * @return string
     */
    
    public static function call_id() {
        
        $id = md5( NONCE_SALT.time() );
        return $id;
        
    }
    
    /**
     * Register Plugin URL Option.
     *    
     * @return bool
     */
    
    public static function pluginURL() {
        
        if(!update_option(OPTION_URL, PLUGIN_URL)) {
            return false;
        }
        
        return true;
        
    }
    
}
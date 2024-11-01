<?php

/*
|--------------------------------------------------------------------------
| Class: Page
|--------------------------------------------------------------------------
|
| This class handles all interactions with the plugins admin page.
|
*/

class Page {
    
    /**
     * Adds a link to the page in the Plugin menu of the admin area.
     *    
     * @return null
     */
    
    public static function menu() {
        
        add_plugins_page(PAGE_TITLE, PAGE_MENU_TITLE, PAGE_CAPABILITY, PAGE_MENU_SLUG, array("Page", "render"));
        
    }
    
    /**
     * Renders the plugins page for the user.
     *    
     * @return null
     */
    
    public static function render() {
        
        self::GETquery();        
        include(PLUGIN_ABS."templates/page.php");
        
    }
    
    /**
     * Function that handles GET key value pairs.
     *    
     * @return null
     */
    
    public static function GETquery() {
        
        /* Handle reporting_remote request */
        
        if (isset($_GET["reporting_remote"])) {
            
            switch($_GET["reporting_remote"]) {
                case "disable":
                    API::reporting("disable");
                    break;
                case "enable":
                    API::reporting("enable");
                    break;
            }
            
        }
        
        /* Handle push_update request */
        
        if (isset($_GET["push_update"])) {
            
            switch($_GET["push_update"]) { // Allow for more in the future
                case "now":
                    API::push_update();
                    break;
            }
            
        }

    }
    
}
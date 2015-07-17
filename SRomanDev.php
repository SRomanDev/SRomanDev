<?php
/*
Plugin Name: SRomanDev
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Lesson
Version: 1.0
Author: SRomanDev
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/
if ( ! function_exists( 'add_action' ) ) exit;
if( ! class_exists( 'SRomanDev' ) ) {
    /**
     * Class SRomanDev
     */
    final class SRomanDev{
        public function __construct(){
        }
        //Активация плагина
        public static function SRPluginActivation(){
            error_log("Активация плагина");
        }
        //Деактивация плагина
        public static function SRPluginDeactivation(){
            error_log("Деактивация плагина");
        }
        //Удаление плагина
        public static function SRPluginUninstall(){
            error_log("Удаление плагина");
        }
    }
}
if( class_exists( 'SRomanDev' ) ) {
    $SRomanDev = new SRomanDev();
    /**
     * Добавляем базовые хуки при активации, деактивации и удалении плагина
     **/
    register_activation_hook( __FILE__,     array( 'SRomanDev',  'SRPluginActivation' ) );
    register_deactivation_hook( __FILE__,   array( 'SRomanDev',  'SRPluginDeactivation' ) );
    register_uninstall_hook( __FILE__,      array( 'SRomanDev',  'SRPluginUninstall' ) );
}
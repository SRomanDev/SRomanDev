<?php
/**
 * Created by PhpStorm.
 * User: freeman
 * Date: 18.07.15
 * Time: 0:00
 */

class SRControlPanel {
    public function __construct($type){
        switch($type){
            case "site":
                break;
            case "admin":
                add_action('admin_menu', array( &$this, 'SRActionAdminMenu'));
                break;
        }
    }
    public function SRActionAdminMenu(){
        add_menu_page(
            _x('Test',  'add_menu_page page title' , SRInit::$textdomain),
            _x('Test',     'add_menu_page menu title' , SRInit::$textdomain ),
            'manage_options',
            SRInit::$textdomain,
            array(@SRControlPanel,'srOptionsPage'),
            SRInit::$url .'/images/menu.png');
    }
    public static function srOptionsPage(){

    }

}
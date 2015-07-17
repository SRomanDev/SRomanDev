<?php
if ( ! function_exists( 'add_action' ) ) exit;
require_once SRInit::$path.'/includes/SRAutoloader.php';
SRAutoloader::init();
class SRControlPanel {
    public static $SRExcursiopediaPanel;
    public function __construct($type){
        switch($type){
            case "site":
                break;
            case "admin":
                self::$SRExcursiopediaPanel = new SRExcursiopediaPanel();
                /**
                 * Этот экшен(действие) используется для добавления элементов
                 * (подменю и меню опций) в структуру меню панели администратора.
                 * Запускается после обработки базового меню панели администратора.
                 */
                add_action('admin_menu', array( &$this, 'SRActionAdminMenu'));
                break;
        }
    }
    public function SRActionAdminMenu(){
        /**
         * add_menu_page( $page_title, $menu_title, $capability, $menu_slug,
         *                  $function, $icon_url, $position );
         * Добавляет пункт (страницу) верхнего уровня в меню админ-панели
         * (в один ряд с постами, страницами, пользователями и т.д.).
         */
        add_menu_page(
            _x('Excursiopedia',  'add_menu_page page title' , SRInit::$textdomain),
            _x('Excursiopedia',     'add_menu_page menu title' , SRInit::$textdomain ),
            'manage_options',
            SRInit::$textdomain,
            array(self::$SRExcursiopediaPanel,'srAdminPluginPage'),
            SRInit::$url .'/images/menu.png');
    }
}
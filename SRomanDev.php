<?php
/*
Plugin Name: Excursiopedia
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Earn with Excursiopedia.com!
Version: 1.0
Author: SRomanDev
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2

*/
if ( ! function_exists( 'add_action' ) ) exit;
if( ! class_exists( 'SRomanDev' ) ) {
    require_once dirname(__FILE__).'/includes/SRAutoloader.php';
    SRAutoloader::init();
    /**
     * Class SRomanDev
     */
    final class SRomanDev{
        public $SRRequestApi;
        public function __construct(){
            new SRInit(__FILE__);
            new SRLocalization();
            $this->SRRequestApi = new SRRequestApi();
            /*
             * error_log(print_r($this->SRRequestApi->getActivities(), true));
             * error_log(print_r($this->SRRequestApi->getActivitiesID(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getCategories(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getCities(array('page' => false, 'limit' => false,
                'name' => 'Ки')),true));
             * error_log(print_r($this->SRRequestApi->getCitiesID(array('id' =>false)), true));
             * error_log(print_r($this->SRRequestApi->getCitiesID(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getCountries(), true));
             * error_log(print_r($this->SRRequestApi->getCountriesID(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getDiscussions(), true));
             * error_log(print_r($this->SRRequestApi->getDiscussionsID(array('id' => 17)), true));
             * error_log(print_r($this->SRRequestApi->getOrders(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getProductCategories(), true));
             * error_log(print_r($this->SRRequestApi->getProducts(), true));
             * error_log(print_r($this->SRRequestApi->getProductsID(array('id' => 17)), true));
             * error_log(print_r($this->SRRequestApi->getRegions(), true));
             * error_log(print_r($this->SRRequestApi->getRegionsID(array('id' => 1)), true));
             * error_log(print_r($this->SRRequestApi->getUsersID(array('id' => 123)), true));
            */


            if ( is_admin() ) :
                new SRControlPanel("admin");
                new SRSettings();
                else:
                    endif;
            //error_log(print_r(get_option(SRInit::$option_name), TRUE));
        }
        //Активация плагина
        public static function SRPluginActivation(){
            //error_log("Активация плагина");
            /**
             * get_option( $option, $default );
             * Получает значение указанной настройки (опции).
             * Проверим существуют настройки или нет если не
             * сущствует создадим по умолчанию
             */
            if( ! get_option( SRInit::$option_name ) ){
                /**
                 * update_option( $option_name, $newvalue, $autoload );
                 * Обновляет значение настройки (опции) в Базе Данных.
                 */
                update_option( SRInit::$option_name, SRDefault::srDefaultOptions() );
            }
        }
        //Деактивация плагина
        public static function SRPluginDeactivation(){
            error_log("Деактивация плагина");
        }
        //Удаление плагина
        public static function SRPluginUninstall(){
            //error_log("Удаление плагина");
            /**
             * delete_option($name);
             * Удаляет настройки (запись из таблицы wp_options в БД).
             */
            delete_option( SRInit::$option_name );
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
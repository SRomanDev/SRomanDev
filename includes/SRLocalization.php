<?php
if ( ! function_exists( 'add_action' ) ) exit;
require_once SRInit::$path.'/includes/SRAutoloader.php';
SRAutoloader::init();
class SRLocalization {
    public function __construct(){
        // Регистрирует хук-событие.
        // Заставляет указанную PHP функцию сработать в определенное событие
        // init [хук-событие] с версии WP 1.5.2
        // Событие срабатывает после того, как WordPress полностью загружен,
        // но до того, как любые header заголовки были отправлены.
        //plugins_loaded
        add_action('init', array(&$this, 'srLocalization'));
    }
    public function srLocalization(){
        // Загружает строку для перевода плагина.
        load_plugin_textdomain(SRInit::$textdomain, false, SRInit::$path_localization);
    }
}
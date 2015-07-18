<?php
if ( ! function_exists( 'add_action' ) ) exit;
require_once SRInit::$path.'/includes/SRAutoloader.php';
class SRSettings {
    //Поля
    public static $SRFields;
    //НАстройки
    public $SROptions;
    public function __construct(){
        /**
         * admin_init срабатывает до любых других hooks, когда
         * пользователь получает доступ в админку. Этот обработчик
         * не обеспечивает каких-либо параметров, так что он может
         * быть использован только для обратного вызова указанной функции.
         */
        add_action( 'admin_init', array( &$this, 'srActionSettingsInit' ) );
        self::$SRFields = new SRFields();
        $this->SROptions = get_option( SRInit::$option_name );
    }
    /**
     * Инициализация настроек
     */
    public function srActionSettingsInit(){
        /**
         * register_setting( $option_group, $option_name, $sanitize_callback );
         * Регистрирует новую опцию и callback функцию (функцию обратного вызова)
         * для обработки значения опции при её сохранении в БД.
         */
        register_setting( 'SRGeneralSettings',
            SRInit::$option_name,
            array(@SRSettings,'srSaveOption'));
        /**
         * add_settings_section( $id, $title, $callback, $page );
         * Создает новый блок (секцию), в котором выводятся опции (настройки).
         */
        add_settings_section( 'sr_settings_config_id', '', '', 'sr_settings_config' );
        /**
         * add_settings_field( $id, $title, $callback, $page, $section, $args );
         * Создает поле опции для указанной страницы и указанного блока (секции).
         */
        add_settings_field('tp_config_name',
            _x('Name','settings_fields',SRInit::$textdomain),
            array(&self::$SRFields ,'srFieldName'),
            'sr_settings_config', 'sr_settings_config_id' );
        add_settings_field('tp_config_email',
            _x('Email','settings_fields',SRInit::$textdomain),
            array(&self::$SRFields ,'srFieldEmail'),
            'sr_settings_config', 'sr_settings_config_id' );
        add_settings_field('tp_config_affiliate_key',
            _x('Affiliate Key','settings_fields',SRInit::$textdomain)
            , array(&self::$SRFields ,'srFieldAffiliateKey'),
            'sr_settings_config', 'sr_settings_config_id' );
        add_settings_field('tp_config_api_key',
            _x('Api Key','settings_fields',SRInit::$textdomain),
            array(&self::$SRFields ,'srFieldApiKey'),
            'sr_settings_config', 'sr_settings_config_id' );
        add_settings_field('tp_config_local',
            _x('Localization','settings_fields',SRInit::$textdomain)
            , array(&self::$SRFields ,'srFieldLocal'),
            'sr_settings_config', 'sr_settings_config_id' );
    }
    /**
     * Сохранение настроек
     * @param $input
     * @return array
     */
    public static function srSaveOption($input){
        $SROptions = get_option( SRInit::$option_name );
        $result = array_merge($SROptions,$input);
        $message = NULL;
        $type = NULL;
        $type = 'updated';
        $message = 'Настройки обновлены';
        /**
         * add_settings_error( $setting, $code, $message, $type );
         * Регистрирует сообщение о проверке опции, чтобы позднее
         * показать это сообщение пользователю. Обычно такое
         * сообщение - это ошибка проверки данных.
         */
        add_settings_error(
            'SRGeneralSettings',
            esc_attr( 'settings_updated' ),
            $message,
            $type
        );
        return $result;
    }
}
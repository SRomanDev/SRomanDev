<?php
if ( ! function_exists( 'add_action' ) ) exit;
class SRInit {
    // FILE
    public static $file;
    // slug названия плагина
    public static $plugin_slug;
    //Строка перевода плагина
    public static $textdomain;
    //Путь к папке
    public static $path;
    //Путь к папке локализаций
    public static $path_localization;
    //Урл к папке
    public static $url;
    //Ключь для записи версии плагина
    public static $option_version;
    //Ключь для записи настроек плагина
    public static $option_name;
    //Версия плагина
    public static $version;
    //Ajax url
    public static $ajaxUrl;
    /**
     * @param $file
     */
    public function __construct($file) {
        self::$file = $file;
        self::init();
    }
    private static function init() {
        self::init_path( self::$file );
        self::$plugin_slug      =   preg_replace( '/[^\da-zA-Z]/i', '_',  basename( self::$file, '.php' ) );
        self::$textdomain       =   str_replace( '_', '-', self::$plugin_slug );
        self::$option_version   =   self::$plugin_slug . '_version';
        self::$option_name      =   self::$plugin_slug . '_options';
        self::$ajaxUrl = admin_url('admin-ajax.php');
        self::$path_localization = plugin_basename(dirname(self::$file).'/localization/');
    }
    /**
     *  Получаем путь и url к данному файлу
     * @param string $path
     * @param array $url
     * @return mixed
     */
    public static function init_path( $path = __FILE__, $url = array() ) {
        $path               =   dirname( $path );
        $path               =   str_replace( '\\', '/', $path );
        $explode_path       =   explode( '/', $path );

        $current_dir        =   $explode_path[count( $explode_path ) - 1];
        array_push( $url, $current_dir );

        if( $current_dir == basename(WP_CONTENT_DIR) ) {
            $path           =   '';
            $directories    =   array_reverse( $url );
            foreach( $directories as $dir ) {
                $path       =   $path . '/' . $dir;
            }
            self::$path     =   str_replace( '//', '/', ABSPATH . $path);
            self::$url      =   get_bloginfo('url') . $path;
        } else {
            return self::init_path( $path, $url );
        }
    }
}
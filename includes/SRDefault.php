<?php
if ( ! function_exists( 'add_action' ) ) exit;
class SRDefault {
    public static function srDefaultOptions(){
        $defaults = array(
            'config' => array(
                'name' => '',
                'email' => '',
                'affiliate_key' => '',
                'api_key' => '',
                'local' => 'ru'
            )
        );
        $defaults = apply_filters('sromandev_defaults', $defaults );
        return $defaults;
    }
}
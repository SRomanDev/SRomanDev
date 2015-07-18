<?php
if ( ! function_exists( 'add_action' ) ) exit;
require_once SRInit::$path.'/includes/SRAutoloader.php';

class SRFields {
    public $options;
    public function __construct(){
        $this->options = get_option( SRInit::$option_name );
    }
    public function srFieldName(){
        ?>
        <input type="text" class=""
               name="<?php echo SRInit::$option_name;?>[config][name]"
               value="<?php echo esc_attr($this->options['name']['name']) ?>" />
        <?php
    }
    public function srFieldEmail(){
        ?>
        <input type="text" class=""
               name="<?php echo SRInit::$option_name;?>[config][email]"
               value="<?php echo esc_attr($this->options['name']['email']) ?>" />
        <?php
    }
    public function srFieldAffiliateKey(){
        ?>
        <input type="text" class=""
               name="<?php echo SRInit::$option_name;?>[config][affiliate_key]"
               value="<?php echo esc_attr($this->options['name']['affiliate_key']) ?>" />
        <?php
    }
    public function srFieldApiKey(){
        ?>
        <input type="text" class=""
               name="<?php echo SRInit::$option_name;?>[config][api_key]"
               value="<?php echo esc_attr($this->options['name']['api_key']) ?>" />
        <?php
    }
    public function srFieldLocal(){
        /**
         * selected( $val1, $val2, $echo );
         * Сравнивает 2 значения, если они совпадают выводит
         * строку "selected='selected'". Для использования в
         * выпадающем списке <select>, в теге <option>.
         */
        ?>
        <select name="<?php echo SRInit::$option_name;?>[config][local]" class="">
            <option value="ru" <?php selected( $this->options['config']['local'], "ru"); ?>>
                <?php _e("Russian", SRInit::$textdomain); ?>
            </option>
            <option value="en" <?php selected( $this->options['config']['local'], "en"); ?>>
                <?php _e("English", SRInit::$textdomain); ?>
            </option>
        </select>
    <?php
    }
}
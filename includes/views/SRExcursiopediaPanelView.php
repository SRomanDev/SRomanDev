<?php
    /**
     * settings_errors( $setting, $sanitize, $hide_on_update );
     * Выводит на экран сообщения (уведомления и ошибки)
     * зарегистрированные функцией add_settings_error().
     */
    settings_errors( 'SRGeneralSettings' );
?>
<div class="wrap">
    <form action="options.php" id="SRomanDevSettings" method="POST">
        <?php
            /**
             * settings_fields( $option_group );
             * Выводит скрытые поля формы на странице настроек
             * (option_page, _wpnonce, ...).
             */
            settings_fields('SRGeneralSettings');
        ?>
        <?php
            /**
             * do_settings_sections( $page );
             * Выводит на экран все блоки опций,
             * относящиеся к указанной странице настроек в админ-панели.
             */
            do_settings_sections('sr_settings_config');
        ?>
        <div id="submit-wrapper">
            <?php
            /**
             * submit_button( $text, $type, $name, $wrap, $other_attributes )
             * Генерирует код кнопки.
             */
                submit_button(__('Save changes',SRInit::$textdomain), 'primary',
                    'submit', true, array( 'id' => 'SRSaveSettings' ));
            ?>
        </div>
    </form>
</div>
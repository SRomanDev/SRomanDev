<?php
class SRAutoloader {
    public static $loader;
    public function __construct(){
        /**
         * spl_autoload_register — Регистрирует заданную функцию в
         * качестве реализации метода __autoload()
         */
        spl_autoload_register(array($this, 'srIncludes'));
    }
    /** ***/
    public static function init(){
        if(self::$loader == NULL)
            self::$loader = new self();
        return self::$loader;
    }
    /**
     * @param $class
     */
    public function srIncludes($class){
        $classfile = dirname(__FILE__).'/'.$class.'.php';
        if (file_exists($classfile)) {
            include $classfile;
        }

    }
}
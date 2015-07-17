<?php

class SRExcursiopediaPanel {
    public function __construct(){}
    public function srAdminPluginPage(){
        require SRInit::$path .'/includes/views/SRExcursiopediaPanelView.php';
    }
}
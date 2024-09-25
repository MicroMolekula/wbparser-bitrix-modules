<?php

Class krasikoff_wbparser extends CModule
{
    var $MODULE_ID = "krasikoff.wbparser";
    var $MODULE_VERSION = "1.0.0";
    var $MODULE_VERSION_DATE = "24.09.2024";
    var $MODULE_NAME = "Парсер маркетплейса Wildberries";
    var $MODULE_DESCRIPTION = "Парсер маркетплейса Wildberries";
    var $errors;

    function DoInstall()
    {
        \Bitrix\Main\ModuleManager::RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        \Bitrix\Main\ModuleManager::UnRegisterModule($this->MODULE_ID);
    }
}
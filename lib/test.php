<?php

namespace Krasikoff\WbParser;

class Test
{

    public static function get()
    {
        $config = \Bitrix\Main\Config\Configuration::getInstance('krasikoff.wbparser');
        return $config->get('selenium_uri');
    }

}

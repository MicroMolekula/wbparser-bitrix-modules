<?php

require 'vendor/autoload.php';

Bitrix\Main\Loader::registerAutoloadClasses(
	"krasikoff.wbparser",
	array(
		"Krasikoff\\WbParser\\Test" => "lib/test.php",
	)
);

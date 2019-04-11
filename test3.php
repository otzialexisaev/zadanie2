<?php

spl_autoload_register(function ($class_name) {
	include $class_name . '.php';
});

	$test = new ConfigReader();
	$test->updateConfigs("test.php");

?>
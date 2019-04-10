<?php

spl_autoload_register(function ($class_name) {
	include $class_name . '.php';
});

function init(array $value){
	if($value['source']=="ftp" ){
		return new FTPAdapter($value);
	} else if($value['source']=="http"){
		return new HTTPAdapter($value);
	}
}

foreach(AdapterBase::getConfig("test.php") as $value){
	$setAdapter = init($value);
	$loader = $setAdapter->setLoader();
	$loader->doSomething();
	echo "<hr>";
}
?>
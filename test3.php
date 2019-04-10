<?php

spl_autoload_register(function ($class_name) {
	include $class_name . '.php';
});

// $o = new Adapter("test.php");
// $o->getData();

function init(array $value){
	if($value['source']=="ftp" ){
		return new FTPAdapter($value);
	} else if($value['source']=="http"){
		return new HTTPAdapter($value);
	}
}

foreach(AdapterBase::getConfig("test.php") as $value){
	//print_r($value);
	$setAdapter = init($value);
	//$setAdapter->getData();
	echo "<br>";
	//$setAdapter = AdapterBase::setAdapter($value);
	$loader = $setAdapter->setLoader();
	$loader->doSomething();
	echo "<hr>";
}
?>
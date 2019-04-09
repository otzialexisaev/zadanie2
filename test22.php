<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$o = new Config();
$o->setData("test/test.php");
print_r($o->getData());
echo "<br>";
$o->set("var2", 150);
print_r($o->getData());
echo "<br>";
echo $o->get("var2");

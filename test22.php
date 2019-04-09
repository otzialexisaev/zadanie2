<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$o = new Config("test/test.php");
echo $o->get("var2");
$o->set("var2", 150);
echo "<br>";
echo $o->get("var2");

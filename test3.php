<?php
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    //$prefix = 'Foo\\Bar\\';
    $prefix = '';
    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/';
    //echo  $base_dir."     ->base dir<br>";
    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);
    //echo  $relative_class."     ->relative_class<br>";

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    //echo  $file."     ->file<br>";
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
// // Old autoloader
// spl_autoload_register(function ($class_name) {
//     include $class_name . '.php';
// });

$test = new ConfigReader();
$test->updateConfigs("test.php");

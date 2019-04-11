<?php 

spl_autoload_register(function ($class_name) {
	include $class_name . '.php';
});

$conn = new MysQLDB();
$conn->connect('localhost','root','','test4');

$arrayAll = $conn->getTableContentAsArrayAll('configurations');
print_r($arrayAll);
echo "<br>";
for($i = 0; $i<sizeof($arrayAll); $i++){
  echo $arrayAll[$i]['config']."<br>";
}

$arrayOne = $conn->getTableContentAsArrayOne('configurations', 1);
print_r($arrayOne);
echo "<br>";
echo "id: ".$arrayOne['id'] . "<br>Config: " . $arrayOne['config'] . "<br>";

$arrayOne = $conn->getTableContentAsArrayOne('configurations', 2);
print_r($arrayOne);
echo "<br>";
echo "id: ".$arrayOne['id'] . "<br>Config: " . $arrayOne['config'] . "<br>";
echo "<hr>";
echo "<hr>";

$conn = new PDODB();
$conn->connect('localhost','root','','test4');
$arrayAll = $conn->getTableContentAsArrayAll('configurations');
print_r($arrayAll);
echo "<br>";
for($i = 0; $i<sizeof($arrayAll); $i++){
  echo $arrayAll[$i]['config']."<br>";
}

$arrayOne = $conn->getTableContentAsArrayOne('configurations', 1);
print_r($arrayOne);
echo "<br>";
echo "id: ".$arrayOne['id'] . "<br>Config: " . $arrayOne['config'] . "<br>";


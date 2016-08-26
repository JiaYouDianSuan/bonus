<?php
require_once('Init.php');
//print_R(unserialize($_SESSION['_Registor_']));
$sSql = "select * from bus_finance_payment";
$oResult = Database::create()->query($sSql);

echo "232323";
while(list($iPk,$sNo) = mysql_fetch_array($oResult))

{

    echo $iPk."</br>";

}

?>

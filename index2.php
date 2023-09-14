
<?php
$serverName = "10.51.249.87";
$connectionInfo = array( "Database"=>"NpiNotification_Dev", "UID"=>"NpiNoti_usr01", "PWD"=>"NpiNoti01@2022" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$sql = "INSERT INTO test (id, data) VALUES (?, ?)";
$params = array(1, "some data");

$stmt = sqlsrv_query( $conn, $sql, $params);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}
?>

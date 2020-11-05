<?php

// Array with names

//$a[] = [
//    "paciente"=>"Anastacia",
//    "bps"=>20+round(rand(5,10)),
//    "bpd"=>50+round(rand(5,10)),
//    "temperatura"=>68+round(rand(5,10)),
//    "respiracion"=>7+round(rand(5,10))
//];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "openemr";
$pid = $_GET['pid'];
$inicio = $_GET['inicio']." 00:00:00";
$fin = $_GET['fin']." 00:00:00";
$unidad = $_GET['unidad'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$a=[];
$qlconsult = "SELECT date, ".$unidad. " from form_vitals where date between '".$inicio."' and '".$fin."' and pid=".$pid. " order by date" ;
$result = $conn->query($qlconsult);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($a,[
            $unidad=>$row[$unidad],
            "date"=>date('Y-m-d H:i:s',strtotime($row["date"]))
        ]);
    }
}

$conn->close();

echo json_encode($a);
?>

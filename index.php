<?php

require_once "dbconn.php";
$sql = "select * from item";
$stmt = $conn->query($sql);
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $data) {
    echo $data ['iname'] ."<br>";
    echo $data ['price'] ."<br>";
    echo "<img src ='$item [img_path]>";
}

?>
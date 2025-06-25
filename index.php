<?php

require_once "dbconn.php";

$sql = "SELECT * FROM item";
$stmt = $conn->query($sql);
$stmt->execute();
$items = $stmt->fetchAll();

foreach ($items as $item) {
    echo $item['iname'] . "<br>";
    echo $item['price'] . "<br>";
    echo $item['description'] . "<br>";
    echo $item['quantity'] . "<br>";  // FIXED

    // Display image properly
    $imgPath = htmlspecialchars($item['img_path']); // prevent XSS
    echo $imgPath . "<br>";
    echo "<img src='$imgPath' alt='Item Image' width='150'><br><br>";
}
?>

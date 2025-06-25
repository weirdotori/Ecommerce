<?php
require_once "dbconn.php";
if(!isset($_SESSION))
{
    session_start();
}
try {
    $sql = "select i.item_id, i.iname,
		i.price, i.description,
        i.quantity, i.img_path,
        c.cname as category
        from item i, category c 
        where i.category = c.cid";

    $stmt = $conn->query($sql);
    $items = $stmt->fetchAll();
    //print_r($items);

} catch (PDOException $e) {
    echo $e->getMessage();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php require_once "navbar.php" ?>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-10 mx-auto py-5">
                <?php 
              if (isset($_SESSION['insertSuccess']))
              {
                    echo "<p class='alert alert-success'> $_SESSION[insertSuccess] </p>";
                    unset($_SESSION['insertSuccess']);
              }else if (isset($_SESSION['updateSuccess']))
              {
                    echo "<p class='alert alert-success'> $_SESSION[updateSuccess] </p>";
                    unset($_SESSION['updateSuccess']);
              }else if (isset($_SESSION['deleteSuccess']))
              {
                    echo "<p class='alert alert-success'> $_SESSION[deleteSuccess] </p>";
                    unset($_SESSION['deleteSuccess']);
              }
                
                
                ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Quality</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($items)) {
                            foreach ($items as $item) {
                                echo "<tr>
                                <td>$item[iname]</td>
                                <td>$item[price]</td>
                                <td>$item[description]</td>
                                <td>$item[category]</td>
                                <td>$item[quantity]</td>
                                <td><img src=$item[img_path] style=width:80px; height:80px></td>   
                                <td><a class='btn btn-primary rounded-pill' href=editItem.php?eid=$item[item_id]>Edit</a></td>
                                <td><a class='btn btn-danger rounded-pill' href=editItem.php?did=$item[item_id]>Delete</a></td>

                                </tr>";
                            }
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
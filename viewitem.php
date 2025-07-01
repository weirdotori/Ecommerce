<?php
require_once "dbconn.php";
if(!isset($_SESSION))
{
    session_start();
}

try {
    $sql = "select * from category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    echo $e->getMessage();
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


if(isset($_GET['cate'])) 
{
        $cid = $_GET['cateChoose'];
        try {
            $sql = "SELECT i.item_id, i.iname, i.price, i.description, i.quantity, i.img_path, c.cname as category
                    FROM item i, category c
                    WHERE i.category = c.cid AND c.cid = ?";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$cid]);
            $items = $stmt->fetchAll();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }
}


if(isset($_GET['priceRadio']))
{
    $range = $_GET['priceRange'];
    $sql = "select i.item_id, i.iname,
		i.price, i.description,
        i.quantity, i.img_path,
        c.cname as category
        from item i, category c 
        where i.category = c.cid 
        and i.price between ? and ?";
        
    $lower = 0;
    $upper = 0;
    if($range == 0)
    {
        $lower = 1;
        $upper = 100;
    }
    else if ($range == 1)
    {
        $lower = 101;
        $upper = 200;
    }else if ($range == 2)
    {
        $lower = 201;
        $upper = 300;

    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([$lower, $upper]);
    $items = $stmt->fetchAll();

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
            <div class="col-md-2 py-5">
                <form action="viewitem.php" method="get" class="form border border-primary border-1 rounded">
                <select name="cateChoose" class="form-select">
                    <option>Choose Category</option>
                    <?php
                    if(isset($categories))
                    {
                        foreach($categories as $category)
                        {
                            echo "<option value=$category[cid]>  $category[cname] </option>";
                        }

                    }
                    ?>

                </select>
                <button class="mt-3 btn btn-sm btn-outline-primary rounded-pill" name="cate" type="submit">Submit</button>
                </form>

                <form action="viewitem.php" method="get" class="mt-5 form border border-primary border-1 rounded">
                    <fieldset> 
                        <legend> <h6>Choose Price Range</h6></legend>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="priceRange" value="0">
                        <label class="form-check-label" for="priceRange">
                            $1-$100
                        </label>
                        <br>

                        <input class="form-check-input" type="radio" name="priceRange" value="1">
                        <label class="form-check-label" for="priceRange">
                            $101-$200
                        </label>
                        <br>

                        <input class="form-check-input" type="radio" name="priceRange" value="2">
                        <label class="form-check-label" for="priceRange">
                            $201-$300
                        </label>
                    </div>
                    <button class="mt-3 btn btn-sm btn-outline-primary rounded-pill" name="priceRadio" type="submit">Submit</button>

                    </fieldset>
                </form>

            </div>

            <div class="col-md-10 mx-auto py-5 mb-2">
                <div class="py-2"> <a class="btn btn-primary mb-2" href="insertitem.php"> Add New Item </a>  </div>

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
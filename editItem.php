<?php
require_once "dbconn.php";
if(!isset($_SESSION))
{
    session_start();
}
$sql = "select * from category";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();

if(isset($_GET['did'])) //upon clicking the delete button
{
    $item_id = $_GET['did'];
    $sql = "delete from item where item_id=?";
    $stmt = $conn->prepare($sql);
    $status = $stmt->execute([$item_id]);
    if($status)
    {
        $_SESSION['deleteSuccess'] = "Item with id $item_id has been deleted successfully.";
        header("Location:viewitem.php");
    }
}//delete end

if(isset($_GET['eid']))
{
    $item_id = $_GET['eid']; // id from link
    try{
            $sql = "select i.item_id, i.iname,
		            i.price, i.description, 
		            i.quantity, i.img_path, c.cname as category

                        from item i, category c
                    where i.category = c.cid AND 
                    i.item_id = ?";

                   $stmt = $conn->prepare($sql);
                   $stmt->execute([$item_id]);
                   $item = $stmt->fetch();


    }catch(PDOException $e)
    {
        echo $e->getMessage();
    }

}// end if

// update item

if(isset($_POST['updateItem'])){

             $itemId = $_POST['itemId'];
             // paste here
             $itemName =  $_POST['itemName'];
            $price =  $_POST['price'];
            $quantity =  $_POST['quantity'];
            $description =  $_POST['description'];
            $category =  $_POST['category'];
            $fileName = $_FILES['img']['name']; // accessing filename
            $filePath = "images/".$fileName;// prepare server location    

            // store image in server ( local machine hardisk)
            $status = move_uploaded_file($_FILES['img']['tmp_name'],  $filePath);
     if ($status)// if storing file in a specified directory in a server
     {

        $sql = "update item 
                set iname=?, price=?,
                quantity =?, description =?,
                category =?, img_path=?
                where item_id=?";
        $stmt = $conn->prepare($sql);
       $status = $stmt->execute([$itemName, $price, $quantity, $description, $category, $filePath, $itemId]);
       if($status)
       {
                 $_SESSION['updateSuccess'] = "Item with id $itemId has  been updated successfully !!";
                header("Location:viewItem.php");

       }
    }
        



}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Items</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "navbar.php"; ?>
        </div>
        <div class="row">
          
            <div class="col-md-6 mx-auto">

                <form class="form mt-2 pt-2" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset>
                        <legend>Edit Item</legend>
                        <input type="hidden" name="itemId" value="<?php echo $item['item_id']; ?>">
                        <div class="mb-2">
                            <label for="itemName" class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="itemName" value="<?php  echo $item['iname']; ?>">
                        </div>
                        <div class="mb-2">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" name="price" value="<?php  echo $item['price']; ?>" >
                        </div>
                        <div class="mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description"  class="form-control" placeholder="<?php  echo $item['description']; ?>"></textarea>
                        </div>
                        <div class="mb-2">
                            <label for="category" class="form-label">Category</label>
                            <p>you selected <?php echo $item['category']; ?></p>
                            <select name="category" class="form-select">
                                <option value="">Select Category</option>
                                <?php if (isset($categories)) {
                                    foreach ($categories as $category) {
                                        echo $category['cid'];
                                        echo "<option value=$category[cid]>$category[cname]  </option>";
                                    }
                                }

                                ?>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="<?php echo $item['quantity']; ?>" >
                        </div>
                        <div class="mb-2">
                            <img src="<?php echo $item['img_path']; ?>" alt="">
                            <label for="img" class="form-label">Choose item image</label>
                            <input type="file" class="form-control" name="img">
                        </div>
                      <button type="submit" class="btn btn-primary" name="updateItem">Update Item</button>  

                    </fieldset>
                </form>






            </div>

        </div>
    </div>
</body>

</html>
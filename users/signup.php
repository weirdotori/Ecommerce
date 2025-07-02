<?php
require_once "../dbconn.php";


$cities = array("Yangon", "Mandalay", "Magway", "Myitkyina", "Malamyine");
if(isset($_POST['signUp'])) // it exists when signup button is clicked
{   $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $profile = $_FILES['profile'];
    $filepath = "profile/".$_FILES['profile']['name'];

    try{ // saving file into a specified directory
       $status =  move_uploaded_file($_FILES['profile']['tmp_name'], $filepath);
       if($status)
       {
        $sql = "insert into users values (?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        //userid	username	email	gender	city	phone	profile_path	password	
        $stmt->execute([null, $username, $email, $gender, $city, $phone, $filepath, $password]);
       }
        
    }catch(PDOException $e)
    {
         echo $e->getMessage();
    }
}









?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mx-auto py-3 mt-3">
                <h3 class="text-start">Sign Up</h3>
                <form action="signup.php" method="post" enctype="multipart/form-data">
                    <div class="mb-1">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-1">
                        <label for="phone" class="form-label">phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <p class="text-start fs-4">Choose Gender</p>
                    <div class="mb-1">
                        <input class="form-check-input" type="radio" name="gender">
                        <label class="form-check-label" for="gender">
                            female
                        </label>
                    </div>
                    <div class="mb-1">
                        <input class="form-check-input" type="radio" name="gender">
                        <label class="form-check-label" for="gender">
                            male
                        </label>
                    </div>
                    <div class="mb-1">
                        <select name="city" class="form-select">
                            <option value="">Choose City</option>
                            <?php
                            if(isset($cities))
                            {   foreach($cities as $city)
                                {
                                    echo "<option value=$city>$city</option>";
                                }

                            }


                            ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="profile" class="form-label">Chooose Profile Image</label>
                        <input type="file" name="profile" class="form-control">
                    </div>


                    <div class="mb-1">

                    <button type="submit" name="signUp" class="btn btn-primary">Signup</button>
                    </div>   




                </form>





            </div>
        </div>





    </div>




</body>

</html>
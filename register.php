<?php
session_start();
require_once('config/config.php');
require_once('config/common.php');

if($_POST) {
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
        if(empty($_POST['name'])) {
          $nameError = 'Name cannot be null';
        }
  
        if(empty($_POST['email'])) {
          $emailError = 'Email cannot be null';
        }
  
        if(empty($_POST['password'])) {
           $passwordError = 'Password cannot be null';
         }
         
         if(strlen($_POST['password']) < 4) {
            $passwordError = "Password should be 4 character at least";
         } 
  
      } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $password = password_hash($_POST['password'],PASSWORD_DEFAULT);;

            $stm = $pdo->prepare("
            SELECT * FROM users WHERE email = :email
            ");

            $stm->bindValue(":email", $email);

            if($stm->execute()) {
                $user = $stm->fetch(PDO::FETCH_ASSOC);
            }

            if($user) {
                echo "<script>alert('Email duplicated')</script>";
            } else {
                $stm = $pdo->prepare("
                    INSERT INTO users (name, password, email, role) VALUES
                    (:name, :password, :email, :role)
                ");

                    $result = $stm->execute(
                        array(':name' => $name, ':email' =>$email, ':password' =>$password, ':role'=>0)
                    );

                if($result) {
                    echo "<script>alert('Successfully register;You can login now');window.location.href='login.php';</script>";
                }
            }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body{
            background-color: whitesmoke;
        }
        .d-flex{
            display: flex;
            flex-wrap: wrap;
            height: 100vh;
            justify-content: center;
            align-content: center;
        }
        .finput {
            width: 100%;
            height: 35px;
            outline: 0;
            border: 1px solid rgb(192, 190, 190);
            padding: 4px;
            border-radius: 3px;
        }
        .login{
            padding: 25px;
            background-color : white;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="container">

                <h3 class="text-center mb-4">User Register</h3>

                <div class="row">
                    <div class="col-md-4 mx-auto login">
                        <p class="text-center">Register new account</p>
                        <form action="register.php" method="POST">
                        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                           <div class="form-group">
                           <p style="color:red;"><?php echo empty($nameError) ? '' : '*'.$nameError;?></p>
                                <input type="text" name="name" placeholder="Name" class="finput">
                            </div>

                            <div class="form-group">
                            <p style="color:red;"><?php echo empty($emailError) ? '' : '*'.$emailError;?></p>
                                <input type="email" name="email" placeholder="Email" class="finput">
                            </div>

                            <div class="form-group">
                            <p style="color:red;"><?php echo empty($passwordError) ? '' : '*'.$passwordError;?></p>
                                <input type="password" name="password" placeholder="Enter password" class="finput">
                            </div>

                            <div class="my-4">
                                <button class="btn btn-primary float-right">Register</button>
                            </div>
                            
                            <p><a href="login.php">Go login</a></p>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</body>
</html>

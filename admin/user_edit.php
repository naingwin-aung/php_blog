<?php
  session_start();
  require_once('../config/config.php');
  require_once('../config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }

  if($_SESSION['role'] != 1) {
    header('location: login.php');
  }

  if($_POST) {
    if(empty($_POST['name']) || empty($_POST['email'])) {
      if(empty($_POST['name'])) {
        $nameError = 'Name cannot be null';
      }

      if(empty($_POST['email'])) {
        $emailError = 'Email cannot be null';
      }

    }elseif(!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $passwordError = "Password should be 4 character at least";
    }else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $password = password_hash($_POST['password'],PASSWORD_DEFAULT);;
     if(empty($_POST['role'])) {
        $role = 0;
     } else {
        $role = 1;
     }

      $stm = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id !=".$_GET['id']);
      $stm->bindParam(':email', $_POST['email']);
      $stm->execute();
      $user = $stm->fetch(PDO::FETCH_ASSOC);

      if($user) {
         echo "<script>alert('Email duplicated')</script>";
      } else {
         
        if($password != null) {
          $stm = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id=".$_GET['id']);
        } else {
          $stm = $pdo->prepare("UPDATE users SET name='$name', email='$email', role='$role' WHERE id=".$_GET['id']);
        }
        $result = $stm->execute();
         if(isset($result)) {
            header('location: user.php'); 
         }
      }
    }
  }

   $stmt = $pdo->prepare("SELECT * FROM users WHERE id=" .$_GET['id']);
   $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
?>

    <?php include_once('header.php'); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="user_edit.php?id=<?php echo $result['id']; ?>" method="POST">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">  
                    <label for="">Name</label>
                    <p style="color:red;"><?php echo empty($nameError) ? '' : '*'.$nameError;?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result['name'])?>">
                  </div>

                  <div class="form-group">  
                    <label for="">Email</label>
                    <p style="color:red;"><?php echo empty($emailError) ? '' : '*'.$emailError;?></p>
                    <input type="email" class="form-control" name="email" value="<?php echo escape($result['email'])?>">
                  </div>

                  <div class="form-group">  
                    <label for="">Password</label><br>
                    <small>The user already have a password</small>
                    <p style="color:red;"><?php echo empty($passwordError) ? '' : '*'.$passwordError;?></p>
                    <input type="text" class="form-control" name="password">
                  </div>

                  <?php if($result['role'] == 0): ?>
                     <div class="form-group">  
                     <label for="">Role</label><br>
                     <input type="checkbox" name="role">
                     </div>
                  <?php else: ?>
                     <div class="form-group">  
                     <label for="">Role</label><br>
                     <input type="checkbox" name="role" checked>
                     </div>
                  <?php endif; ?>

                  <div class="form-group">
                    <button class="btn btn-success">SUBMIT</button>
                    <a href="user.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
              </div>  
            </div>

          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

  <?php include('footer.html'); ?>

  
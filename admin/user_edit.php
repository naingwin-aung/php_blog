<?php
  session_start();
  require_once('../config/config.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }

  if($_SESSION['role'] != 1) {
    header('location: login.php');
  }

  if($_POST) {
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
         $stm = $pdo->prepare("UPDATE users SET name=:name, email=:email, password=:password, role=:role WHERE id=".$_GET['id']);
         
         $stm->bindParam(':name', $_POST['name']);
         $stm->bindParam(':email', $_POST['email']);
         $stm->bindParam(':password', $_POST['password']);
         $stm->bindParam(':role', $role);

         $result = $stm->execute();
            
         if($result) {
            header('location: user.php'); 
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
                  <div class="form-group">  
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $result['name'];?>" required>
                  </div>

                  <div class="form-group">  
                    <label for="">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $result['email'];?>" required>
                  </div>

                  <div class="form-group">  
                    <label for="">Password</label>
                    <input type="text" class="form-control" name="password" value="<?php echo $result['password'];?>" required>
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

  
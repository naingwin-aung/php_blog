<?php
  session_start();
  require_once('../config/config.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }

  if($_POST) {
     if(empty($_POST['role'])) {
        $role = 0;
     } else {
        $role = 1;
     }

     $stm = $pdo->prepare("SELECT * FROM users WHERE email=:email");
     $stm->bindParam(':email', $_POST['email']);
     $stm->execute();
     $user = $stm->fetch(PDO::FETCH_ASSOC);

     if($user) {
        echo "<script>alert('Email duplicated')</script>";
     } else {
        $stm = $pdo->prepare("INSERT INTO users(name, password, email, role) VALUES
         (:name, :password, :email, :role)
        ");
        $stm->bindParam(':name', $_POST['name']);
        $stm->bindParam(':password', $_POST['password']);
        $stm->bindParam(':email', $_POST['email']);
        $stm->bindParam(':role', $role);

        if($stm->execute()) {
           echo "<script>alert('Successfully added');window.location.href='user.php';</script>";
        }
     }
  }
?>

    <?php include_once('header.php'); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <form action="user_add.php" method="POST">
                     <div class="form-group">
                     <label for="">Name</label>
                     <input type="text" class="form-control" name="name" required>
                     </div>

                     <div class="form-group">
                     <label for="">Email</label>
                     <input type="text" class="form-control" name="email" required>
                     </div>

                     <div class="form-group">
                     <label for="">Password</label>
                     <input type="text" class="form-control" name="password" required>
                     </div>

                     <div class="form-group">
                     <label for="">Admin</label><br>
                     <input type="checkbox" name="role">
                     </div>

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

                           
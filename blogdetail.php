<?php
  session_start();
  require_once('config/config.php');
  require_once('config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" .$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $blogId = $_GET['id'];

  $stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
  $stmtcmt->execute();
  $resultcmt = $stmtcmt->fetchAll(PDO::FETCH_ASSOC);

  $resultau = [];
  if($resultcmt) {
    foreach($resultcmt as $key => $value) {
      $authorId = $resultcmt[$key]['author_id'];
      $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
      $stmtau->execute();
      $resultau[] = $stmtau->fetch(PDO::FETCH_ASSOC);
    }
  }

  if($_POST) {
    if(empty($_POST['comment'])) {
      if(empty($_POST['comment'])) {
        $cmtError = 'Comment cannot be null';
      }
    } else {
      $comment = $_POST['comment'];

      $stm = $pdo->prepare("
         INSERT INTO comments (context, author_id, post_id) VALUES
         (:context, :author_id, :post_id)
      ");

      $result = $stm->execute(
        array(':context'=>$comment,':author_id'=>$_SESSION['user_id'],':post_id'=>$blogId)
      );
      if($result) {
        header("Location: blogdetail.php?id=".$blogId);
      }
    }
    }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Detail of Blog Post</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important">

    <!-- Main content -->
    
    <section class="content">
      <div class="row">
            <div class="col-md-12">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                <div style="text-align : center;float:none;" class="card-title">
                  <h4><?php echo $result['title'];?></h4>
                </div>
                  <!-- /.user-block -->
                  
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="text-center mb-5">
                    <img src="admin/images/<?php echo $result['image']; ?>" 
                    height="300px !important" alt="Photo">
                  </div>

                  <h5><?php echo $result['content']; ?></h5>
                  
                </div>
                <h5 class="my-4">Comments</h5><hr>
                <!-- /.card-body -->
                <div class="card-footer card-comments">
                  <!-- /.card-comment -->
                  <div class="card-comment">
                    <!-- User image -->
  
                    <?php if($resultcmt): ?>
                      <div class="comment-text ml-0">                  
                        <?php foreach($resultcmt as $key => $value): ?>
                          <span class="username">
                            <?php print_r($resultau[$key]['name']); ?>
                            <span class="text-muted float-right"><?php echo $value['created_at']; ?></span>
                          </span><!-- /.username -->
                        <?php echo $value['context']; ?>
                      <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <!-- /.card-comment -->
                </div>
                <!-- /.card-footer -->
                <div class="card-footer">
                  <form action="" method="POST">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <img class="img-fluid img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="Alt Text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                    <p style="color:red;"><?php echo empty($cmtError) ? '' : '*'.$cmtError;?></p>
                      <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                  <div class="text-right">
                    <a href="index.php" class="btn btn-default mt-4 p-3">Go back To Home Page</a>
                  </div>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer ml-0">
     <!-- To the right -->
     <div class="float-right d-none d-sm-inline">
       <a href="logout.php" class="btn btn-default">Logout</a>
     </div>
     <!-- Default to the left -->
     <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>
   </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>

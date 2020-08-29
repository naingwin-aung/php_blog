<?php
  session_start();
  require_once('config/config.php');
  require_once('config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>User Blog</title>
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1 class="text-center">Blog Site</h1>
      </div><!-- /.container-fluid -->
    </section>

    <?php 
      if(!empty($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
      } else {
        $pageno = 1;
      }
      $numOfrecs = 6;
      $offset = ($pageno -1 ) * $numOfrecs;

      $stm = $pdo->prepare("
        SELECT * FROM posts ORDER BY id DESC
        ");

        if($stm->execute()) {
            $rawResult = $stm->fetchAll();
        }

        $total_pages = ceil(count($rawResult)/ $numOfrecs);

          $stm = $pdo->prepare("
            SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs
          ");

          if($stm->execute()) {
            $result = $stm->fetchAll();
          }
    ?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <?php foreach($result as $results): ?>
          <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div style="text-align : center;float:none;" class="card-title">
                  <h4><?php echo escape($results['title'])?></h4>
                </div>
                <!-- /.user-block -->
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="text-center">
                  <a href="blogdetail.php?id=<?php echo $results['id']; ?>"><img src="admin/images/<?php echo $results['image'];?>" height="200px !important"></a>
                </div>
              </div>
              <!-- /.card-body -->
              <!-- /.card-footer -->
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
        <?php endforeach; ?>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <div class="row float-right mr-0">
                  <nav aria-label= "Page navigation example" class="mt-4">
                      <ul class="pagination">
                          <li class="page-item"> <a href="?pageno=1" class="page-link">First</a></li>
                          <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>"> 
                            <a href="<?php if($pageno <= 1){echo '#';} else {echo '?pageno='.($pageno-1);}?>" class="page-link">Previous</a>
                          </li>
                          <li class="page-item"> <a href="#" class="page-link"><?php echo $pageno; ?></a></li>
                          <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>"> 
                            <a href="<?php if($pageno >=$total_pages){echo '#';} else {echo '?pageno='.($pageno+1);}?>" class="page-link">Next</a>
                          </li>
                          <li class="page-item"> <a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
                      </ul>
                  </nav>
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

<?php
  session_start();
  require_once('../config/config.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
  }
?>

    <!-- Main content -->
    <?php 
      if(!empty($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
      } else {
        $pageno = 1;
      }
      $numOfrecs = 2;
      $offset = ($pageno -1 ) * $numOfrecs;

      if(empty($_POST['search'])){
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
      } else {
        $searchKey = $_POST['search'];
          $stm = $pdo->prepare("
          SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC
          ");

          if($stm->execute()) {
            $rawResult = $stm->fetchAll();
          }

          $total_pages = ceil(count($rawResult)/ $numOfrecs);

          $stm = $pdo->prepare("
          SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs
          ");

          if($stm->execute()) {
            $result = $stm->fetchAll();
          }
      }
      
    ?>
    <?php include_once('header.html'); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="create.php" type="button" class="btn btn-success mb-4">New Blog Posts</a>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 160px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($result): ?>
                      <?php $i= 1;?>  
                        <?php foreach($result as $results): ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $results['title'];?></td>
                                <td>
                                  <?php echo substr($results['content'], 0, 58);?>
                                </td>
                                <td>
                                  <a href="edit.php?id=<?php echo $results['id'];?>" type="button" class="btn btn-warning">Edit</a>
                                  <a href="delete.php?id=<?php echo $results['id'];?>" 
                                  onclick="return confirm('Are you sure you want to delete this item')"
                                  type="button" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>                 
                    <?php endif; ?>      
                  </tbody>
                </table>

                <div class="float-right">
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
                
              </div>
              <!-- /.card-body -->
              
            </div>

          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

  <?php include('footer.html'); ?>

  
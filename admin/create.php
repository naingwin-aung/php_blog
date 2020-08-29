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

    if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
      if(empty($_POST['title'])) {
        $titleError = 'Title cannot be null';
      }

      if(empty($_POST['content'])) {
        $contentError = 'Content cannot be null';
      }

      if(empty($_FILES['image'])) {
        $imageError = 'Image cannot be null';
      }
    } else {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);

        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
          echo "<script>alert('Image must be png, jpg, jpeg')</script>";
        }else {
          move_uploaded_file($_FILES['image']['tmp_name'],$file); 

          $stm = $pdo->prepare("INSERT INTO posts(title, content, image, author_id) VALUES 
          (:title, :content, :image, :author_id)
          ");

          $stm->bindParam(":title" , $_POST['title']);
          $stm->bindParam(":content" , $_POST['content']);
          $stm->bindParam(":image" , $_FILES['image']['name']);
          $stm->bindParam(":author_id" , $_SESSION['user_id']);

          if($stm->execute()) {
            header('location: index.php');
          }
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
                <form action="create.php" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Title</label>
                    <p style="color:red;"><?php echo empty($titleError) ? '' : '*'.$titleError;?></p>
                    <input type="text" class="form-control" name="title">
                  </div>

                  <div class="form-group">
                    <label for="">Content</label>
                    <p style="color:red;"><?php echo empty($contentError) ? '' : '*'.$contentError;?></p>
                    <textarea name="content" id="" cols="30" rows="10" class="form-control"></textarea>
                  </div>

                  <div class="form-group">
                    <label for="">Image</label>
                    <p style="color:red;"><?php echo empty($imageError) ? '' : '*'.$imageError;?></p>
                    <input type="file" name="image">
                  </div>

                  <div class="form-group">
                    <button class="btn btn-success">SUBMIT</button>
                    <a href="index.php" class="btn btn-warning">Back</a>
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

  
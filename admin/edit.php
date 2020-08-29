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
    if(empty($_POST['title']) || empty($_POST['content'])) {
      if(empty($_POST['title'])) {
        $titleError = 'Title cannot be null';
      }

      if(empty($_POST['content'])) {
        $contentError = 'Content cannot be null';
      }

    } else {
      $id = $_GET['id'];
      $title = $_POST['title'];
      $content = $_POST['content'];
      
      if($_FILES['image']['name'] != null) {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);
    
        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
          echo "<script>alert('Image must be png, jpg, jpeg')</script>";
        }else {
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],$file); 
            
            $stm = $pdo->prepare("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id='$id'");
            
            $result = $stm->execute();
          
            if($result) {
                header('location: index.php');
            }
        }
      }else {
            $stm = $pdo->prepare("UPDATE posts SET title='$title', content='$content' WHERE id='$id'");
                
            $result = $stm->execute();
        
            if($result) {
                header('location: index.php');
            }
      }
    }
  }

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=" .$_GET['id']);
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
                <form action="edit.php?id=<?php echo $result['id']; ?>" method="POST" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    
                    <label for="">Title</label>
                    <p style="color:red;"><?php echo empty($titleError) ? '' : '*'.$titleError;?></p>
                    <input type="text" class="form-control" name="title" value="<?php echo escape($result['title'])?>">
                  </div>

                  <div class="form-group">
                    <label for="">Content</label>
                    <p style="color:red;"><?php echo empty($contentError) ? '' : '*'.$contentError;?></p>
                    <textarea name="content" id="" cols="30" rows="10" class="form-control"><?php echo escape($result['content']) ?></textarea>
                  </div>

                  <div class="form-group">
                    <label for="">Image</label>
                    <img src="images/<?php echo $result['image']; ?>" width="150" height="150" alt=""><br><br>
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

  
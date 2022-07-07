<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
error_reporting(0);
	$valid = 1;

    if(empty($_POST['category_name'])) {
        $valid = 0;
        $error_message .= "Category Name can not be empty<br>";
    } else {
		// Duplicate Category checking
    	// current category name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_category_name = $row['category_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_name=? and category_name!=?");
    	$statement->execute(array($_POST['category_name'],$current_category_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'Category name already exists<br>';
    	}
    }
	
	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    $previous_photo = $_POST['previous_photo'];

	if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if($valid == 1) {

    	if($_POST['category_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['category_name']);
    		$category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);;
    	} else {
    		$temp_string = strtolower($_POST['category_slug']);
    		$category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_slug=? AND category_name!=?");
		$statement->execute(array($category_slug,$current_category_name));
		$total = $statement->rowCount();
		if($total) {
			$category_slug = $category_slug.'-1';
		}
		
		unlink('../assets/uploads/'.$previous_photo);

	    	$final_name = 'news-'.$_REQUEST['id'].'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );
    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_category SET category_name=?, category_slug=?, meta_title=?, meta_keyword=?, meta_description=?, photo=? WHERE category_id=?");
		$statement->execute(array($_POST['category_name'],$category_slug,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$final_name,$_REQUEST['id']));

    	$success_message = 'Category is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Kategori Düzenle</h1>
	</div>
	<div class="content-header-right">
		<a href="category.php" class="btn btn-primary btn-sm">Hepsini Göster</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
	$category_name = $row['category_name'];
	$category_slug = $row['category_slug'];
	$meta_title = $row['meta_title'];
	$meta_keyword = $row['meta_keyword'];
	$meta_description = $row['meta_description'];
	$photo = $row['photo'];
}
?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
		
		<p>
		<?php echo $error_message; ?>
		</p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
		<div class="callout callout-success">
		
		<p><?php echo $success_message; ?></p>
		</div>
		<?php endif; ?>

        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

        <div class="box box-info">

            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Kategori Adı <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="category_name" value="<?php echo $category_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Kategori Slug</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="category_slug" value="<?php echo $category_slug; ?>">
                    </div>
                </div>
				<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Kategori Banner</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				            	<?php
				            	if($photo == '') {
				            		echo 'No photo found';
				            	} else {
				            		echo '<img src="../assets/uploads/'.$photo.'" class="existing-photo" style="width:200px;">';	
				            	}
				            	?>
				                <input type="hidden" name="previous_photo" value="<?php echo $photo; ?>">
				            </div>
				        </div>
						
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Değiştir</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <input type="file" name="photo">
				            </div>
				        </div>
                <h3 class="seo-info">SEO Information</h3>
                <div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Title </label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="meta_title" value="<?php echo $meta_title; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Keywords </label>
					<div class="col-sm-9">
						<textarea class="form-control" name="meta_keyword" style="height:100px;"><?php echo $meta_keyword; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Meta Description </label>
					<div class="col-sm-9">
						<textarea class="form-control" name="meta_description" style="height:100px;"><?php echo $meta_description; ?></textarea>
					</div>
				</div>
                <div class="form-group">
                	<label for="" class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success pull-left" name="form1">Güncelle</button>
                    </div>
                </div>

            </div>

        </div>

        </form>



    </div>
  </div>

</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
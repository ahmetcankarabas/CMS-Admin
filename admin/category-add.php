<?php require_once('header.php'); ?>

<?php
error_reporting(0);
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['category_name'])) {
        $valid = 0;
        $error_message .= "Category Name can not be empty<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_name=?");
    	$statement->execute(array($_POST['category_name']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Category Name already exists<br>";
    	}
    }
	
	$path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];


    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }

    if($valid == 1) {

    	// Getting auto increment id for this category
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_category'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

    	if($_POST['category_slug'] == '') {
    		// generate slug
    		$temp_string = strtolower($_POST['category_name']);
    		$category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	} else {
    		$temp_string = strtolower($_POST['category_slug']);
    		$category_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $temp_string);
    	}

    	// if slug already exists, then rename it
		$statement = $pdo->prepare("SELECT * FROM tbl_category WHERE category_slug=?");
		$statement->execute(array($category_slug));
		$total = $statement->rowCount();
		if($total) {
			$category_slug = $category_slug.'-1';
		}
    	$final_name = 'category-'.$ai_id.'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );
		// Saving data into the main table tbl_category
		$statement = $pdo->prepare("INSERT INTO tbl_category (category_name,category_slug,meta_title,meta_keyword,meta_description,photo) VALUES (?,?,?,?,?,?)");
		$statement->execute(array($_POST['category_name'],$category_slug,$_POST['meta_title'],$_POST['meta_keyword'],$_POST['meta_description'],$final_name));
	
    	$success_message = 'Category is added successfully.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Kategori Ekle</h1>
	</div>
	<div class="content-header-right">
		<a href="category.php" class="btn btn-primary btn-sm">Hepsini Göster</a>
	</div>
</section>


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
								<input type="text" class="form-control" name="category_name" placeholder="Example: Health Tips">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Kategori Slug </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="category_slug" placeholder="Example: health-tips">
							</div>
						</div>
						
						<div class="form-group">
				            <label for="" class="col-sm-2 control-label">Kategori Banner</label>
				            <div class="col-sm-6" style="padding-top:6px;">
				                <input type="file" name="photo">
				            </div>
				        </div>
						<h3 class="seo-info">SEO Information</h3>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Title </label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="meta_title">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Keywords </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_keyword" style="height:100px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Meta Description </label>
							<div class="col-sm-9">
								<textarea class="form-control" name="meta_description" style="height:100px;"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Gönder</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>
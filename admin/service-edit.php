<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['name'])) {
		$valid = 0;
		$error_message .= 'Name can not be empty<br>';
	}

	if(empty($_POST['description'])) {
		$valid = 0;
		$error_message .= 'Description can not be empty<br>';
	}







	if($valid == 1) {

		$statement = $pdo->prepare("SELECT * FROM tbl_service WHERE id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_name = $row['name'];
		}






			$statement = $pdo->prepare("UPDATE tbl_service SET name=?, description=? WHERE id=?");
    		$statement->execute(array($_POST['name'],$_POST['description'],$_REQUEST['id']));


		$success_message = 'Service is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_service WHERE id=?");
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
		<h1>Edit Service</h1>
	</div>
	<div class="content-header-right">
		<a href="service.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_service WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$name              = $row['name'];
	$slug              = $row['slug'];
	$description       = $row['description'];
	$short_description = $row['short_description'];
	$photo             = $row['photo'];
	$banner            = $row['banner'];
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
				<input type="hidden" name="current_photo" value="<?php echo $photo; ?>">
				<input type="hidden" name="current_banner" value="<?php echo $banner; ?>">
				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Üst Başlık <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php echo $name; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Açıklama <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="description" id="editor1"><?php echo $description; ?></textarea>
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
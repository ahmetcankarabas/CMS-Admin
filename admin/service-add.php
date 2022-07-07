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

		// getting auto increment id
		$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_service'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}



    	// if slug already exists, then rename it


		

        

		$statement = $pdo->prepare("INSERT INTO tbl_service (name,description) VALUES (?,?)");
		$statement->execute(array($_POST['name'],$_POST['description']));
			
		$success_message = 'Service is added successfully!';

		unset($_POST['name']);
		unset($_POST['slug']);
		unset($_POST['description']);
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Body Alt İçerik</h1>
	</div>
	<div class="content-header-right">
		<a href="service.php" class="btn btn-primary btn-sm">Hepsini Göster</a>
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
							<label for="" class="col-sm-2 control-label">Name <span>*</span></label>
							<div class="col-sm-6">
								<input type="text" autocomplete="off" class="form-control" name="name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];} ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Açıklama <span>*</span></label>
							<div class="col-sm-9">
								<textarea class="form-control" name="description" id="editor1"><?php if(isset($_POST['description'])){echo $_POST['description'];} ?></textarea>
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
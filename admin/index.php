<?php require_once('header.php'); ?>

<section class="content-header">
  <h1>Dashboard</h1>
</section>

<?php 
$statement = $pdo->prepare("SELECT * FROM tbl_user");
$statement->execute();
$total_user = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_category");
$statement->execute();
$total_category = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_news");
$statement->execute();
$total_news = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_photo");
$statement->execute();
$total_photo = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_video");
$statement->execute();
$total_video = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_attorney");
$statement->execute();
$total_attorney = $statement->rowCount();

?>

<section class="content">

  <div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Toplam Kullanıcı</span>
          <span class="info-box-number"><?php echo $total_user; ?></span>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-tasks"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Toplam Kategori</span>
          <span class="info-box-number"><?php echo $total_category; ?></span>
        </div>
      </div>
    </div>


    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-file-text"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Toplam Ürün</span>
          <span class="info-box-number"><?php echo $total_news; ?></span>
        </div>
      </div>
    </div>
    
    
    <div class="col-md-4 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Toplam Marka</span>
          <span class="info-box-number"><?php echo $total_attorney; ?></span>
        </div>
      </div>
    </div>
  </div>


</section>

<?php require_once('footer.php'); ?>
<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>İçerik</h1>
	</div>
	<div class="content-header-right">
		<a href="testimonial-add.php" class="btn btn-primary btn-sm">Ekle</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="30">ID</th>
								<th>Başlık</th>
								<th width="100">Açıklama</th>
								<th width="100"></th>
								<th width="100"></th>
								<th></th>
								<th width="80">İşlem</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT
														
														id,
														name,
														comment

							                           	FROM tbl_testimonial
							                           	
							                           	");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['comment']; ?></td>
									<td></td>
									<td></td>
									<td></td>
									<td>										
										<a href="testimonial-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Düzenle</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="testimonial-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Sil</a>  
									</td>
								</tr>
								<?php
							}
							?>							
						</tbody>
					</table>
				</div>
			</div>
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
                <p>Are you sure want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
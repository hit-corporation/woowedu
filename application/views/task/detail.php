<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<h6>Detail Tugas</h6>
		<br>
		<h5><?=$task['subject_name']?></h5>
		<br>
		<div class="row border rounded p-3">
			<div class="col">Nama Guru: <?=$task['teacher_name']?></div>
			<div class="col">
				<div class="row">
					<div class="col">Dibuat: <?=date('d M Y H:i', strtotime($task['available_date']))?></div>
					<div class="col">Batas Pengumpulan: <span class="bg-danger rounded text-white"><?=date('d M Y H:i', strtotime($task['due_date']))?></span></div>
				</div>
			</div>



			<br><br>
			<p class="mt-4">Catatan:</p>
			<p><?=$task['note']?></p>

			<br><br>
			<p>File Soal Tugas:</p>

			<div class="container">
			<?php 
				if(!empty($task['task_file'])){

					function contains($array, $string) {
						return count(array_intersect($array, explode(".", $string)));
					}
				
					$string = $task['task_file'];
					$array = array('mp4', 'ogv', 'webm');
					$i = contains($array, $string);
					echo ($i) ? '<video width="500" height="500" controls src="'.base_url('assets/files/teacher_task/').$task['task_file'].'"></video>' : '<a href="'.base_url('assets/files/teacher_task/').$task['task_file'].'">Download File</a><br><embed width="191" height="207" name="plugin" src="'.base_url('assets/files/teacher_task/').$task['task_file'].'" type="application/pdf">';
				
				}else{
					echo 'Tidak Ada File Materi Tugas';
				}
			?>
			</div>

		</div>


		<div class="row border rounded mt-3 p-4">
			<div class="container">
				<p>Jawaban Tugas: </p>
				<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Kumpulkan Tugas</button>
			</div>
		</div>
		
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Upload Jawaban Tugas</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div> 
			<form action="<?=base_url('task/store_file')?>" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="mb-3">
						<label for="formFile" class="form-label">Pilih File dari komputer</label>
						<input type="hidden" name="task_id" value="<?=$task['task_id']?>">
						<input type="hidden" name="class_id" value="<?=$task['class_id']?>">
						<input class="form-control" type="file" name="formFile" id="formFile">
					</div>
					<div class="form-floating">
						<textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="task_note"></textarea>
						<label for="floatingTextarea2">Comments</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	// alert message login
	<?php if(!empty($_SESSION['simpan'])) : ?>

		<?php if($_SESSION['simpan']['success'] == true){ ?>
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success"><?=$_SESSION['simpan']['message']?></span>',
				timer: 5000
			});
		<?php }else{ ?>
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger"><?=$_SESSION['simpan']['message']?></span>',
				timer: 5000
			});
		<?php } ?>

	<?php endif; ?>
</script>

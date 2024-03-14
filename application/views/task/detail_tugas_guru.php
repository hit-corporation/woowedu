<link rel="stylesheet" href="<?=base_url('assets/node_modules/datatables.net-dt/css/dataTables.dataTables.min.css')?>">

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		
		<div class="card mt-5">
			<h6 class="card-header"><?=$task['subject_name']. ' - '. $task['code']?></h6>
			<div class="card-body">
				<div class="col">Nama Guru: <?=$task['teacher_name']?></div>
				<div class="col">
					<div class="row">
						<div class="col">Dibuat: <?=date('d M Y H:i', strtotime($task['available_date']))?></div>
						<div class="col">Batas Pengumpulan: <span class="bg-danger rounded text-white py-1 px-2"><?=date('d M Y H:i', strtotime($task['due_date']))?></span></div>
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
						echo ($i) ? '<video width="500" height="500" controls src="'.base_url('assets/files/teacher_task/'.$task['teacher_id'].'/').$task['task_file'].'"></video>' : '<a href="'.base_url('assets/files/teacher_task/'.$task['teacher_id'].'/').$task['task_file'].'">Download File</a><br><embed width="191" height="207" name="plugin" src="'.base_url('assets/files/teacher_task/'.$task['teacher_id'].'/').$task['task_file'].'" type="application/pdf">';
					
					}else{
						echo 'Tidak Ada File Materi Tugas';
					}
				?>
				</div>
			</div>
		</div>

		<div class="card mt-3">
			<h5 class="card-header">List Murid yang mengumpulkan</h5>
			<div class="card-body">
				<table class="table table-striped" id="myTable">
					<thead>
						<tr>
							<th>No</th>
							<th>Nis</th>
							<th>Nama</th>
							<th>Tanggal Mengumpulkan</th>
							<th>File Jawaban</th>
							<th>Nilai</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; ?>
						<?php foreach ($data_siswa_kelas as $key => $val) : ?>
							<tr>
								<td><?=$i?></td>
								<td><?=$val['nis']?></td>
								<td><?=$val['student_name']?></td>
								<td><?=(isset($val['detail_jawaban']['task_submit'])) ? date('d M Y, H:i', strtotime($val['detail_jawaban']['task_submit'])) : '' ?></td>
								<td class="text-center">
									<?php if(isset($val['detail_jawaban']['task_submit'])) : ?>
										<a href="<?=base_url('assets/files/student_task/'.$task['class_id'].'/'.$val['detail_jawaban']['task_file'])?>"><img src="<?=base_url('assets/images/paper.png')?>" alt="" width="30"></a>
									<?php endif ?>	
								</td>
								<td class="text-center"><?=(isset($val['detail_jawaban']['task_nilai'])) ? $val['detail_jawaban']['task_nilai'] : '' ?></td>
								<td class="text-center">
									<a class="mx-1" href="<?=base_url('task/detail/'.$task['task_id'])?>"><i class="bi bi-eye-fill"></i></a>
									<a class="mx-1" href="<?=base_url('task/detail/'.$task['task_id'])?>"><i class="bi bi-pencil"></i></a>
								</td>
							</tr>
						<?php $i++ ?>		
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>

		
	</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?=base_url('assets/node_modules/datatables.net/js/dataTables.min.js')?>"></script>

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

	$(document).ready(function () {
		let table = new DataTable('#myTable');
		$('.dt-layout-cell.dt-start').addClass('d-none');
	});
</script>

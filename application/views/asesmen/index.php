<style>
	p.card-text {
		font-size: 14px;
	}
	.card {
		cursor: pointer;
	}
	.card:hover{
		background-color: rgba(181, 244, 252, 0.32);
		transform: scale(1.03);
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<div class="container mt-3">
		<h5>Lembar Asesmen</h5>
		<form class="row mt-5 mb-2 mx-1" name="frm-filter">

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<div class="form-group mb-0"> 
					<input type="text" class="form-control" name="s_title" id="s_title" placeholder="Nama asesmen">
				</div>
			</div>  

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
					<option value="">-- pilih mapel --</option>
					<?php foreach($mapels as $mapel): ?>
						<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
					<?php endforeach ?>
				</select>
			</div>

			<?php if(isset($_SESSION['teacher_id'])): ?>
				<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
					<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
						<option value="">-- pilih kelas --</option>
						<?php foreach($classes as $class): ?>
							<option value="<?=$class['class_id']?>"><?=$class['class_name']?></option>
						<?php endforeach ?>
					</select>
				</div>
			<?php endif ?>

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<button id="cari" class="btn btn-primary text-white" type="submit" ><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		</form>

	

		<!-- lembar asesmen -->
		<div class="container p-4">
			<div class="row border rounded">

				

				<nav class="d-flex justify-content-start">
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<button class="nav-link active" id="nav-asesmen-standar-tab" data-bs-toggle="tab" data-bs-target="#nav-sesi" type="button" role="tab" aria-controls="nav-sesi" aria-selected="true"><i class="fa-regular fa-calendar-days h6"></i> Lembar Asesmen Standar</button>
						<button class="nav-link" id="nav-asesmen-khusus-tab" data-bs-toggle="tab" data-bs-target="#nav-materi-guru" type="button" role="tab" aria-controls="nav-materi-guru" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Lembar Asesmen Khusus</button>
					</div>
				</nav>

				<?php if(isset($_SESSION['teacher_id'])): ?>
				<div class="row mb-3">
					<div class="col-12 d-flex flex-row-reverse">
						<button class="btn btn-sm btn-primary text-white" id="create" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Buat Asesmen</button>
					</div>
				</div>
				<?php endif ?>

				<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">
					
					<div class="tab-pane fade show active" id="nav-sesi" role="tabpanel" aria-labelledby="nav-asesmen-standar-tab" tabindex="0">

						<table class="table table-rounded" id="table-asesmen-standar" style="width: 100%;">
							<thead>
								<tr>
									<th>Nama Asesmen</th>
									<th>Nama Matapelajaran</th>
									<th>Dibuat Pada</th>
									<th>Tipe</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<div class="tab-pane fade p-3" id="nav-materi-guru" role="tabpanel" aria-labelledby="nav-asesmen-khusus-tab" tabindex="0">
						<table class="table-rounded" id="table-asesmen-khusus" style="width: 100%;">
							<thead>
								<tr>
									<th>Nama Matapelajaran</th>
									<th>Nama Materi</th>
									<th>Ditugaskan</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					
					
				</div>
			</div>
		</div>
	</div>


	<!-- Modal Create New --> 
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Buat Lembar Asesmen Baru</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">

						<div class="col-6">
							<a href="<?=base_url().'asesmen/create_standar'?>">
								<div class="card h-100">
									<div class="card-body">
										<h6 class="card-title"><i class="fa fa-note-sticky"></i>Lembar Asesmen Standar</h6>
										<p class="card-text">Melalui pilihan ini, Bapak/Ibu dapat membuat Lembar Asesmen dengan berbagai macam bentuk soal. 
											Kunci jawaban untuk Lembar Asesmen yang dihasilkan juga akan tersedia.</p>
									</div>
								</div>
							</a>
						</div>

						<div class="col-6">
							<a href="<?=base_url().'asesmen/create_khusus'?>">
								<div class="card h-100">
									<div class="card-body">
										<h6 class="card-title"><i class="fa fa-note-sticky"></i>Lembar Asesmen Khusus</h6>
										<p class="card-text">Melalui pilihan ini Bapak/Ibu dapat membuat Lembar Asesmen khusus yang dapat Bapak/Ibu kirim sebagai tugas kelas. 
											Nilai tugas siswa akan langsung dilaporkan kepada Bapak/Ibu setelah siswa selesai mengerjakan tugas.</p>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

</section>

<script>
	// create swall alert
	$(document).ready(function () {
		<?php if(!empty($_SESSION['success']) && $_SESSION['success']['success'] == true) : ?>
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});
	
		<?php endif; ?>
	
		<?php if(!empty($_SESSION['success']) && $_SESSION['success']['success'] == false) : ?>
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});
		<?php endif; ?>
	});
</script>

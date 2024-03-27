<style>
	.left-side, .right-side{
		border: 1px solid rgba(212,209,209,0.6);
	}
	.card p {
		font-size: 14px;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<div class="container mt-3">
		<p>Lembar Asesmen > <strong>Hasilkan lembar asesmen</strong></p>

		<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 left-side">
				<form class="row mt-3 mb-2 mx-1" name="frm-filter">

				<label class="form-label">Bank Soal <span class="text-danger">*</span></label>

					<div class="form-group mb-3"> 
						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="standar" checked>
							<label class="form-check-label" for="flexRadioDefault1">
								Bank Soal Standar
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="sendiri" >
							<label class="form-check-label" for="flexRadioDefault2">
								Buat soal anda sendiri
							</label>
						</div>
					</div>
					

					<div class="form-group mb-3"> 
						<label for="a_title" class="form-label">Judul lembar Asesmen <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="a_title" id="a_title" placeholder="Nama asesmen">
					</div>

					<div class="form-group mb-3"> 
						<label for="select-mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
						<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
							<option value="79">TIK</option>
							<?php foreach($mapels as $mapel): ?>
								<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
							<?php endforeach ?>
						</select>
					</div>

					<?php if(isset($_SESSION['teacher_id'])): ?>
					<div class="form-group mb-3"> 
						<label for="select-mapel" class="form-label">Pilih kelas <span class="text-danger">*</span></label>
						<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
							<?php foreach($classes as $class): ?>
								<option value="<?=$class['class_id']?>"><?=$class['class_name']?></option>
							<?php endforeach ?>
						</select>
					</div>
					<?php endif ?>

					<div class="mb-3">
						<label for="deskripsi" class="form-label">Deskripsi</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 right-side">
				<div class="row mt-2">
					<div class="col">
						<div class="btn-group" role="group" aria-label="Basic mixed styles example">
							<button type="button" class="text-white btn btn-danger tambah-pertanyaan d-none">+ Pertanyaan</button>
							<button type="button" class="text-white btn btn-success tambah-bagian-baru">+ Bagian Baru</button>
							<button type="button" class="text-gray btn btn-warning"><i class="fa fa-save"></i> Simpan Draft</button>
							<button type="button" class="text-white btn btn-success"><i class="fa fa-eye"></i> Pantinjau</button>
							<button type="button" class="text-white btn btn-primary"><i class="fa fa-arrow-up"></i> Publish</button>
						</div>
					</div>
				</div>

				<div class="testpaper-sectionContainer border mt-4 p-4">
					<p>Bagian 1</p>

					<div class="row">
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
							<div class="form-group mb-3"> 
								<label for="a_title" class="form-label">Jenis pertanyaan <span class="text-danger">*</span></label>
								<select class="form-select" name="a_jenis_pertanyaan" id="a_jenis_pertanyaan">
									<option value="1">Pilihan Ganda</option>
									<option value="2">Uraian</option>
								</select>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
							<div class="form-group mb-3"> 
								<label for="a_jumlah_petanyaan" class="form-label">Jumlah pertanyaan <span class="text-danger">*</span></label>
								<input type="number" class="form-control" name="a_jumlah_petanyaan" id="a_jumlah_petanyaan" placeholder="Masukan jumlah">
							</div>
						</div>
						<div class="col text-end">
							<button type="button" class="btn btn-sm btn-primary text-white pilih-pertanyaan" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Pilih pertanyaan</button>
						</div>
					</div>

					<div class="row mt-4">
						<div class="content-1">

						</div>
					</div>
				</div>
			</div>
		</div>
	
	</div>


	<!-- Modal Tambah Soal --> 
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Pilih soal</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col">
							<table id="tabelPilihSoal" class="table w-100">
								<thead>
									<tr>
										<th>code</th>
										<th>tema</th>
										<th>sub tema</th>
										<th>pertanyaan</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
	
								</tbody>
							</table>
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

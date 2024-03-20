<section class="explore-section section-padding" id="section_2">

	<div class="container mt-3">
		<h5>Materi Saya</h5>

		<form class="row mt-5 mb-2" name="frm-filter">
			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
					<option value="">-- All --</option>
					<?php foreach($mapels as $mapel): ?>
						<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<button id="cari" class="btn btn-primary text-white" type="submit" ><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		</form>

		<div class="row mb-3">
			<div class="col-12 d-flex flex-row-reverse">
				<button class="btn btn-sm btn-primary text-white" id="create" data-bs-toggle="modal" data-bs-target="#exampleModal">buat materi baru</button>
			</div>
		</div>

		<div class="row border rounded">
			<table class="table" id="myTable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nama Materi</th>
						<th>File / Tautan</th>
						<th>Terakhir di update</th>
						<th>Ukuran</th>
						<th>Tindakan</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>

	<!-- Modal Create New --> 
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Buat Materi Baru</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form name="form-add" method="POST" action="materi/store_materi_saya" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="subject_id" class="form-label">Mapel *</label>
							<select class="form-select" name="subject_id" id="subject_id" aria-label="Pilih Matapelajaran">
								<?php foreach($mapels as $mapel): ?>
									<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="mb-3">
							<input type="hidden" name="materi_id" value="">
							<label for="input_materi" class="form-label">Nama / Judul Materi *</label>
							<input type="text" class="form-control" id="input_materi" name="input_materi">
						</div>
						
						<div class="mb-3">
							<label for="input_file" class="form-label">Lampiran *</label>
							<input type="file" class="form-control" id="input_file" name="input_file">
							<div id="emailHelp" class="form-text">Max ukuran file: 100 MB</div>
						</div>
						<div>
							<button type="submit" class="btn btn-primary btn-sm text-white">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

	<!-- Modal Relasi -->
	<section class="modal fade" id="modal-relasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0">
				<div class="modal-header bg-success">
					<h5 class="modal-title text-capitalize text-light text-shadow">Atur Relasi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form name="form-relasi" id="form-relasi" class="d-flex flex-column">
					<div class="modal-body">
						<div  id="div_relasi">
						</div>  
						<input type="hidden" name="a_materi_id" id="relasi_materi_id" />
						<input type="hidden" name="xsrf" id="relasi_xsrf" />
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary btn-sm text-white" id="save-relasi">simpan</button>
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- end modal relasi-->

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

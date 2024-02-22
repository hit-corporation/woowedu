<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php 
	$user_level = $this->session->userdata('user_level');
	$user_level = ($user_level == 3) ? true : false;
?>

<section class="explore-section section-padding" id="section_2">
	<div class="container">

		<div class="col-12 text-center">
			<h4 class="mb-4">Buat Jadwal Sesi Baru</h4>
		</div>

		<div class="col-12">
			<form id="form-create-news" action="">
	
				<input type="hidden" id="id" name="id" value="<?=isset($data['sesi_id']) ? $data['sesi_id'] : '' ?>">
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Judul</label>
					<input type="text" class="form-control" id="title" name="title" value="<?=isset($data['sesi_title']) ? $data['sesi_title'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
				</div>
 

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<div class="row">
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Tanggal</label>
							<input type="date"  class="form-control" id="tanggal" name="tanggal" value="<?=isset($data['sesi_date']) ? $data['sesi_date'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div>
	
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Jam Mulai</label>
							<input type="time" class="form-control" id="jamstart" name="jamstart" value="<?=isset($data['sesi_jam_start']) ? $data['sesi_jam_start'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div> 
	
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Jam Akhir</label>
							<input type="time" class="form-control" id="jamend" name="jamend" value="<?=isset($data['sesi_jam_end']) ? $data['sesi_jam_end'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div>
					</div>
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<!-- <div class="mb-4 col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<label for="kelas" class="form-label">Kelas</label>
						<select class="form-control" name="kelas" id="kelas"></select>
					<!-- </div> -->
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<!-- <div class="mb-4 col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<label for="materi" class="form-label">Materi</label>
						<select class="form-control" name="materi" id="materi"></select>
					<!-- </div> -->
				</div>
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Catatan</label>
					<!-- <textarea rows="5" class="form-control" id="keterangan" name="keterangan"><?//=isset($data['sesi_note']) ? $data['sesi_note'] : ''?></textarea>  -->
					<div id="keterangan" class="form-control mb-3"><?=isset($data['sesi_note']) ? $data['sesi_note'] : '' ?></div>
				</div>
				
				<!-- <div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Lampiran</label>
					<input type="file" class="form-control" id="lampiran" name="lampiran"  >
				</div> -->

				<div class="mb-3">
					<?php if($user_level) : ?>
						<a class="btn btn-primary text-white" type="submit" name="save">Simpan</a>
					<?php endif ?>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- Include the selectize library -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize Quill editor -->
<script>
	var quill = new Quill('#keterangan', {
		theme: 'snow'
	});

	$('a[name="save"]').on('click', function(){
		let title 		= $('#title').val(); 
		let tanggal 	= $('#tanggal').val(); 
		let jamstart 	= $('#jamstart').val(); 
		let jamend 		= $('#jamend').val(); 
		let materi_id 	= $('#materi').val();
		let class_id 	= $('#kelas').val();
		let keterangan 	= quill.container.firstChild.innerHTML;
		let id 			= $('#id').val();

		$.ajax({
			type: "POST",
			url: BASE_URL+"sesi/create",
			data: {
				title: title,
				tanggal: tanggal,
				jamstart: jamstart,
				jamend: jamend,
				materi_id: materi_id,
				class_id: class_id,
				keterangan: keterangan,
				id: id
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">${response.message}</span>',
						timer: 5000
					});
					window.location.href = BASE_URL+'sesi';
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: '<span class="text-warning">${response.message}</span>',
						timer: 5000
					});
					window.location.href = BASE_URL+'sesi';
				}
			}
		});
	});

	// ################################# COMBO BOX MATERI #################################
	let user_level = <?=$this->session->userdata('user_level')?>;
	if(user_level != 3){
		$('select[name="materi"]').select2({disabled: true});
	}

	$('select[name="materi"]').select2({
        theme: "bootstrap-5",
        data: materi,
        placeholder: 'Pilih Mapel',
        allowClear: true
    });

	// ################################# AJAX GET KELAS #################################
	$.ajax({
		type: "GET",
		url: BASE_URL+"admin/API/Kelas/get_all",
		data: {},
		dataType: "JSON",
		success: function (response) {
			$.each(response.data, function (i, val) { 
				$('#kelas').append(`<option value="${val.class_id}">${val.class_name}</option>`);
			});
		}
	});

	// ################################# AJAX GET MATERI #################################
	$.ajax({
		type: "GET",
		url: BASE_URL+"/materi/getAllMateri",
		data: {},
		dataType: "JSON",
		success: function (response) {
			let materi_id = <?=isset($data['materi_id']) ? $data['materi_id'] : 0 ?>;
			$.each(response, function (i, val) { 
				if(materi_id == val.materi_id){
					$('#materi').append(`<option value="${val.materi_id}" selected>${val.subject_name} - ${val.title}</option>`);
				}else{
					$('#materi').append(`<option value="${val.materi_id}">${val.subject_name} - ${val.title}</option>`);
				}
			});
		}
	});
</script>

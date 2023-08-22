

<section class="explore-section section-padding" id="section_2">
	<div class="container mt-5">
		<div class="col-12 text-center">
			<h4 class="mb-4">Profile Saya</h4>
	</div>

		<div class="row">

			<div class="col-lg-4 col-md-4 border p-5 rounded">
				<div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<button class="nav-link active" id="v-pills-info-dasar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info-dasar" type="button" role="tab" aria-controls="v-pills-info-dasar" aria-selected="true">Informasi Dasar</button>
					
					<?php if ($user_data['user_level'] == 3) : ?>
					<button class="nav-link" id="v-pills-wali-akun-tertaut-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wali-akun-tertaut" type="button" role="tab" aria-controls="v-pills-wali-akun-tertaut" aria-selected="false">Wali Akun Tertaut</button>
					<?php endif ?>

					<button class="nav-link" id="v-pills-change-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-change-password" type="button" role="tab" aria-controls="v-pills-change-password" aria-selected="false">Kata Sandi</button>
				</div>
			</div>

			<div class="col-lg-8 col-md-8 border p-5 rounded">
				<div class="tab-content w-100 ps-5" id="v-pills-tabContent" style="border-left: 1px;">
					<div class="tab-pane fade show active ms-4" id="v-pills-info-dasar" role="tabpanel" aria-labelledby="v-pills-info-dasar-tab" tabindex="0">
						<form id="student-profile" action="">

							<input type="hidden" id="user_id" name="user_id" value="<?=$user_data['userid']?>">
							<input type="hidden" id="user_lavel" name="user_level" value="<?=$user_data['user_level']?>">

							<div class="user-img mb-5">
								<div class="custom-file">
									<img class="rounded" id="previewImg" src="<?= isset($user_data['photo']) ? base_url('assets/images/users/'.$user_data['photo']) : base_url('assets/images/user.png') ?>" alt="" width="200"><br>	
									<input class="mt-2" type="file" id="userfile" name="userfile">
								</div>
							</div>
	
							<!-- NAMA LENGKAP - JIKA USER LEVEL MURID -->
							<?php if($user_data['user_level'] == 4) : ?>
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="full_name" class="form-label">Nama Lengkap</label>
								<input type="text" class="form-control" id="full_name" name="full_name" value="<?=isset($user_data['student_name']) ? $user_data['student_name'] : ''?>" disabled>
							</div>
							<?php endif ?>

							<!-- NAMA LENGKAP - JIKA USER LEVEL GURU -->
							<?php if($user_data['user_level'] == 3) : ?>
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="full_name" class="form-label">Nama Lengkap</label>
								<input type="text" class="form-control" id="full_name" name="full_name" value="<?=isset($user_data['teacher_name']) ? $user_data['teacher_name'] : ''?>" disabled>
							</div>
							<?php endif ?>

							<!-- NAMA LENGKAP - JIKA USER LEVEL ORTU -->
							<?php if($user_data['user_level'] == 5) : ?>
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="full_name" class="form-label">Nama Lengkap</label>
								<input type="text" class="form-control" id="full_name" name="full_name" value="<?=isset($user_data['name']) ? $user_data['name'] : ''?>" disabled>
							</div>
							<?php endif ?>

	
							<!-- NIS - JIKA USER LEVEL MURID -->
							<?php if($user_data['user_level'] == 3) { ?>
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="nis" class="form-label">Nomor Induk Siswa</label>
									<input type="text" class="form-control" id="nis" name="nis" value="<?=isset($user_data['nis']) ? $user_data['nis'] : ''?>" disabled>
								</div>
							<?php } ?>
	
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" value="<?=isset($user_data['email']) ? $user_data['email'] : ''?>">
								<span>Ini adalah alamat email utama Anda dan akan digunakan untuk mengirim email pemberitahuan</span>
							</div>
	
							<!-- ALAMAT - JIKA USER LEVEL MURID -->
							<?php if($user_data['user_level'] == 3) { ?> 
								<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
									<label for="address" class="form-label">Alamat</label>
									<input type="text" class="form-control" id="address" name="address" disabled>
								</div>
							<?php } ?>
	
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="school" class="form-label">Sekolah</label>
								<input type="text" class="form-control" id="school" name="school" disabled value="<?=isset($user_data['sekolah_nama']) ? $user_data['sekolah_nama'] : ''?>">
							</div>
	
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="phone" class="form-label">Nomor Telepon</label>
								<input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor Telepon" value="<?=isset($user_data['phone']) ? $user_data['phone'] : ''?>">
							</div>
	
							<div class="mb-3">
								<button class="btn btn-success" name="save-profile">Simpan Perubahan</button>
							</div>
						</form>
				
					</div>

					<div class="tab-pane fade" id="v-pills-wali-akun-tertaut" role="tabpanel" aria-labelledby="v-pills-wali-akun-tertaut-tab" tabindex="0">
						
						<form id="parent-profile" action="">

							<input type="hidden" id="user_id" name="user_id" value="<?=$user_data['userid']?>">

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_name" class="form-label">Nama Orang Tua</label>
								<input type="text" class="form-control" id="parent_name" name="parent_name" value="<?=isset($user_data['parent_name']) ? $user_data['parent_name'] : ''?>" disabled>
							</div>
	
							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_email" class="form-label">Email</label>
								<input type="email" class="form-control" id="parent_email" name="parent_email" placeholder="Masukan email orang tua" value="<?=isset($user_data['parent_email']) ? $user_data['parent_email'] : ''?>">
							</div>

							<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
								<label for="parent_phone" class="form-label">Nomor Telepon Orang Tua</label>
								<input type="text" class="form-control" id="parent_phone" name="parent_phone" placeholder="Nomor Telepon Orang Tua" value="<?=isset($user_data['parent_phone']) ? $user_data['parent_phone'] : ''?>">
							</div>
	
							<div class="mb-3">
								<a class="btn btn-success" name="save-parent">Simpan Perubahan</a>
							</div>
						</form>
						

					</div>
					
					<div class="tab-pane fade" id="v-pills-change-password" role="tabpanel" aria-labelledby="v-pills-change-password-tab" tabindex="0">
						<form id="form-change-password" action="">
							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="old_password" class="form-label">Kata Sandi</label>
								<input type="hidden" name="user_id" value="<?=$user_data['userid']?>">
								<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Masukan sandi saat ini">
							</div>
								
							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="new_password" class="form-label">Kata Sandi Baru</label>
								<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukan sandi baru">
							</div>

							<div class="mb-3 col-lg-6 col-md-10 col-sm-12 col-xs-12">
								<label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
								<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Masukan kembali sandi">
							</div>

							<div class="mb-3">
								<button class="btn btn-success" name="change_password">Simpan Perubahan</button>
							</div>
						</form>	
						
				</div>
			</div>
		</div>

	</div>
	
</section>
       
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

	$('#userfile').bind('change', function(){
		// JIKA FILE MELEBIHI 1 MB MAKA FILE TIDAK AKAN DI UPLOAD

		if(this.files != undefined){
			if(this.files[0].size > 1000000){
				alert('Ukuran file melebihi 1MB, silahkan pilih file yang lebih kecil');
				this.value = '';
				$('#previewImg').attr('src', '<?= base_url() ?>/assets/images/transparent.jpg');
			} else{
				var file = $("#userfile").get(0).files[0];
				if(file){
					var reader = new FileReader();
					reader.onload = function(){
						$("#previewImg").attr("src", reader.result);
					}
					reader.readAsDataURL(file);
				}
			}
		}
	});

	$('button[name="save-profile"]').on('click', function(e){
		e.preventDefault();
		let formData = $('#student-profile')[0];
		let form = new FormData(formData);
		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/store',
			data: form,
			contentType: false,
 		   	processData: false,
			success: function (response) {
				let res = JSON.parse(response);
				if(res.success == true){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});
				}
			}
		});
	});

	$('button[name="change_password"]').on('click', function(e){
		e.preventDefault();
		let formData = $('#form-change-password')[0];
		let form = new FormData(formData);
		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/change_password',
			data: form,
			contentType: false,
 		   	processData: false,
			success: function (response) {
				let res = JSON.parse(response);
				if(res.success == true){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${JSON.stringify(res.message)}</span>`,
						timer: 5000
					});
				}
			}
		});
	});

	$('a[name="save-parent"]').on('click', function(){
		let formData = $('#parent-profile')[0];
		let form = new FormData(formData);

		$.ajax({
			type: "POST",
			url: BASE_URL + 'user/store_parent',
			data: form,
			contentType: false,
 		   	processData: false,
			success: function (response) {
				let res = JSON.parse(response);
				if(res.success == true){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${res.message}</span>`,
						timer: 5000
					});
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${JSON.stringify(res.message)}</span>`,
						timer: 5000
					});
				}
			}
		});
	});

</script>

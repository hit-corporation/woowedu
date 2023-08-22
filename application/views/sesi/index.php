<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
			<button class="btn btn-lg btn-success rounded"><i class="bi bi-plus-circle-fill"></i> Bergabung dengan kelas baru</button>
		</div>
	</div>

	<div class="row ">

		<div class="col-lg-6 col-md-6 col-sm-12 mb-5">
			<div class="input-group mb-3">
				<select class="form-select" name="select-mapel" id="basic-usage" aria-label="Pilih Matapelajaran">
					<option  value="1" selected>Kelas VI Matematika (Kumer)</option>
					<option  value="2" selected>Kelas VI Bahasa Indonesia (Kumer)</option>
					<option  value="3" selected>Kelas VI IPA (Kumer)</option>
					<option  value="4" selected>Kelas VI IPS (Kumer)</option>
					<option  value="5" selected>Kelas VI Bahasa Inggris (Kumer)</option>
					<option  value="5" selected>Kelas VI Bahasa Inggris (Kumer)</option>
				</select>
				
				<button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
			</div>
		</div>
	</div>

	<div class="row">

		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="sesi-guru-tab" data-bs-toggle="tab" data-bs-target="#sesi-guru-tab-pane" type="button" role="tab" aria-controls="sesi-guru-tab-pane" aria-selected="true">
					<i class="bi bi-calendar2-week me-2"></i>Sesi Guru
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">
					<i class="bi bi-pen-fill me-2"></i>Tugas
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">
					<i class="bi bi-book-half me-2"></i>Materi Guru
				</button>
			</li>
			
		</ul>

		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="sesi-guru-tab-pane" role="tabpanel" aria-labelledby="sesi-guru-tab" tabindex="0">
				Ini adalah sesi guru
			</div>
			<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
				Ini adalah Tugas
			</div>
			<div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
				Ini adalah Materi Guru
			</div>
		</div>








		<!-- <div class="container border rounded p-3">
			<span class="sesi-class active" id="tab-guru">Sesi Guru</span>
			<span class="sesi-class" id="tab-tugas">Tugas</span>
			<span class="sesi-class" id="tab-materi">Materi Guru</span>

			<div class="tab-content">
				<div class="row mt-5" aria-label="tab-guru d-block">
					<p>tes</p>
					<p>tes</p>
					<p>tes</p>
					<p>tes</p>
				</div>
	
				<div class="row mt-5" aria-label="tab-guru d-none">
					<p>tes 2</p>
					<p>tes 2</p>
					<p>tes 2</p>
					<p>tes 2</p>
				</div>
	
				<div class="row mt-5" aria-label="tab-guru d-none">
					<p>tes 3</p>
					<p>tes 3</p>
					<p>tes 3</p>
					<p>tes 3</p>
				</div>
			</div>
		</div> -->
	</div>


</div>

	
</section>

<script>
	$('#basic-usage').select2({
		theme: "bootstrap-5",
		width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		placeholder: $( this ).data( 'placeholder' ),
	});

	$('#tab-guru').on('click', function(){
		let tabContent = $('.tab-content')[0].children;
		$.each(tabContent, function (i, val) { 
			if(tabContent[i].hasClass('d-block')){
				tabContent[i].removeClass('d-block');
			}else{
				tabContent[i].addClass('d-none');
			}
		});

		
	});
</script>

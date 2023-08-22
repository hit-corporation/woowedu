<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<div class="row ">
		<div class="col-lg-6 col-md-6 col-sm-12 mb-2">
			<select class="form-select" name="select-class" aria-label="Pilih Kelas">
				<option  value="1" selected>Kelas 1</option>
				<option value="2">Kelas 2</option>
				<option value="3">Kelas 3</option>
			</select>
		</div>

		
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
		<!-- <div class="col"> -->
			<?php for($i=0; $i<10; $i++):?>
			<div class="col-lg-4 col-md-6">
				<div class="card rounded border mb-4">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-3 col-xs-3">
							<div class="container mt-2">
								<img src="<?=base_url()?>assets/images/faq_graphic.jpg" alt="" width="90" height="125">
							</div>
						</div>
						<div class="col-lg-7 col-md-5 col-sm-9 col-xs-9">
							<p class="title">Buku Interaktif: PR Bahasa Indonesia X Semester 1 2022 </p>
							<p class="font-weight-bold fs-14 p-3">Kelas 10 Bahasa Indonesia (Interaktif)</p>
						</div>
					</div>
				</div>
			</div>
			<?php endfor?>
		<!-- </div> -->
	</div>


</div>

	
</section>

<script>
	$('#basic-usage').select2({
		theme: "bootstrap-5",
		width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		placeholder: $( this ).data( 'placeholder' ),
	});
</script>

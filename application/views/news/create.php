<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<section class="explore-section section-padding" id="section_2">
	
	<div class="container">

		<div class="col-12 text-center">
			<h4 class="mb-4">Buat Pengumuman Baru</h4>
		</div>
		
		<div class="col-12">
			<form id="form-create-news" action="">
	
				<input type="hidden" id="id" name="id" value="<?=isset($data['id']) ? $data['id'] : '' ?>">
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Judul</label>
					<input type="text" class="form-control" id="title" name="title" value="<?=isset($user_data['title']) ? $user_data['title'] : ''?>">
				</div>

				<!-- Create the editor container -->
				<div id="editor" class="form-control mb-3"></div>
	
				<div class="mb-3">
					<a class="btn btn-success" type="submit" name="save">Simpan</a>
				</div>
			</form>
		</div>

	</div>

</section>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Initialize Quill editor -->
<script>
	var quill = new Quill('#editor', {
		theme: 'snow'
	});

	$('a[name="save"]').on('click', function(){
		let title 	= $('#title').val();
		let isi 	= quill.container.firstChild.innerHTML;
		let id 		= $('#id').val();

		$.ajax({
			type: "POST",
			url: BASE_URL+"news/create",
			data: {
				title: title,
				isi: isi,
				id: id
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${response.message}</span>`,
						timer: 5000
					});
					window.location.href = BASE_URL+'news';
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${response.message}</span>`,
						timer: 5000
					});
					window.location.href = BASE_URL+'news';
				}
			}
		});
	});
</script>

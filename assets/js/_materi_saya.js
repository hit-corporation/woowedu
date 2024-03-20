let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
var is_update = false;

$(document).ready(function () {
	let table = $('#myTable').DataTable({
		ajax: BASE_URL + 'materi/list_materi_saya',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: 'title',
			},
			{
				data: 'materi_file',
				render(data, row, type, meta) {
					return `<a href="${BASE_URL+'assets/files/materi/'+data}">
						<img src="${BASE_URL+'assets/images/paper.png'}" width="30">
					</a>`;
				}
			},
			{
				data: 'edit_at'
			},
			{
				data: null,
				render(row, data, type, meta) {
					return Math.round(row.file_size/1000) + ' KB'
				}
			},
			{
				data: null,
				render(data, row, type, meta) {
					var view = '<div class="btn-group btn-group-sm float-right">'+
                                '<button class="btn btn-warning relasi_teacher"><i class="fa-solid fa-share-from-square text-white"></i></button>' +
                                '<button class="btn btn-success edit_materi"><i class="fa-solid fa-pencil text-white"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_materi"><i class="fa-solid fa-trash text-white"></i></button>' +
                            '</div>';
               	 	return view;
				}
			}
		],
	});

	/**
	 * clik button create
	 * reset form
	 */
	$('#create').on('click', function(){
		form.reset();
	});

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(1).search($('select[name="select-mapel"]').val()).draw();
	});

	/**
	 * SUBMIT
	 */
	$("#save-relasi").click(function(){
		$.ajax({
			url: BASE_URL + 'materi/set_relasi',
			type: 'POST',
			data: $("#form-relasi").serialize(),
			contentType: 'application/x-www-form-urlencoded',
			beforeSend(xhr) {
							Swal.fire({
								html: 	'<div class="d-flex flex-column align-items-center">'
								+ '<span class="spinner-border text-primary"></span>'
								+ '<h3 class="mt-2">Loading...</h3>'
								+ '<div>',
								showConfirmButton: false,
								width: '10rem'
								});
			},
			success(resp) {
				
				if(resp.success){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">'+resp.message+'</span>',
						timer: 5000
					});
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: '<span class="text-danger">'+resp.message+'</span>',
						timer: 5000
					});
				}
			},
			error(err) {
				var response = JSON.parse(err);
				Swal.fire({
					type: response.message,
					title: '<h5 class="text-danger text-uppercase">'+response.message+'</h5>',
					html: response.message
				});
			},
			complete() {
				table.ajax.reload();
			}
		});
	});	 
	
	// button relasi di klik
	$('#myTable tbody').on('click', '.btn.relasi_teacher', e => {
		
		let row = table.row($(e.target).parents('tr')).data(); 
		$('#div_relasi').load(BASE_URL + 'materi/relasi?id='+row.teacher_id+'&materi_id='+row.materi_id);
		$('#relasi_materi_id').val(row.materi_id);
		$('#modal-relasi').modal('show');
		
	});

	/** 
	 * Delete Materi 
	*/
	$('#myTable tbody').on('click', '.btn.delete_materi',function(e){
		let row = table.row($(e.target).parents('tr')).data();
		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus" 
		}).then(reslt => {
			if(!reslt.value)
						return false;
			$.ajax({
				type: "POST",
				url: BASE_URL + 'materi/delete',
				data: {materi_id: row.materi_id},
				dataType: "JSON",
				contentType: 'application/x-www-form-urlencoded',
				beforeSend(xhr, obj) {
					Swal.fire({
						html: 	'<div class="d-flex flex-column align-items-center">'
						+ '<span class="spinner-border text-primary"></span>'
						+ '<h5 class="mt-2">Loading...</h5>'
						+ '<div>',
						showConfirmButton: false,
						width: '20rem'
					});
				},
				success: function (response) {
					Swal.fire({
						icon: 'success',
						title:`<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: response.message
					});
				},
				error(err) {
					if(err.status == 500){
						Swal.fire({
							icon: 'error',
							title:`<h5 class="text-${err.statusText} text-uppercase">Error ${err.status}</h5>`,
							html: err.statusText
						});
					}
				},
				complete() {
					table.ajax.reload();
				}
			});
		});
	});

	/** 
	 * Edit Materi 
	*/
	$('#myTable tbody').on('click', '.btn.edit_materi', e => {
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		form['subject_id'].value = row[0].subject_id;
		form['materi_id'].value = row[0].materi_id;
		form['input_materi'].value = row[0].title;

		$('#exampleModal').modal('show');
	});

});


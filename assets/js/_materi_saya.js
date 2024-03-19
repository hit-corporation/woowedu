let csrfToken = document.querySelector('meta[name="csrf_token"]') ;

$(document).ready(function () {
	let table = $('#myTable').DataTable({
		ajax: BASE_URL + 'materi/list_materi_saya',
		serverSide: true,
		processing: true,
		columns: [
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
                                '<button class="btn btn-success edit_teacher"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_teacher"><i class="bx bx-trash font-size-12"></i></button>' +
                            '</div>';
               	 	return view;
				}
			}
		],
	});

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(1).search($('select[name="select-mapel"]').val()).draw();
	});

	/**
	 * SAVE RELASI
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
});

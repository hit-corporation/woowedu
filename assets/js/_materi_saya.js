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
		
	$('#myTable tbody').on('click', '.btn.relasi_teacher', e => {
		
		let row = table.row($(e.target).parents('tr')).data(); 
		$('#div_relasi').load(BASE_URL + 'materi/relasi?id='+row.teacher_id);
		$('#relasi_teacher_id').val(row.teacher_id);
		$('#modal-relasi').modal('show');
		
	});
});

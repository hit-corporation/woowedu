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
					return `<span class="share-icon"><i class="fa-solid fa-share-from-square"></i></span>`;
				}
			}
		],
	});

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(1).search($('select[name="select-mapel"]').val()).draw();
	});

	
});

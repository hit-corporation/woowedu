let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
var is_update = false;

$(document).ready(function () {
	
	/**
	 * radio button bank soal
	 */
	$('input[name="flexRadioDefault"]').on('click', function(e){
		if(e.currentTarget.value == 'sendiri'){
			$('.tambah-pertanyaan').removeClass('d-none');
			$('.tambah-bagian-baru').addClass('d-none');
		}else{
			$('.tambah-pertanyaan').addClass('d-none');
			$('.tambah-bagian-baru').removeClass('d-none');
		}
	});
	

	let table = $('#tabelPilihSoal').DataTable({
		ajax: BASE_URL + 'asesmen/getAll',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'soal_id',
				visible: false
			},
			{
				data: 'tema_title',
			},
			{
				data: 'sub_tema_title'
			},
			{
				data: 'question'
			},
			{
				data: null,
				render(data, row, type, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
                                <button class="btn btn-warning detail_soal"><i class="fa-solid fa-eye text-white"></i></button>
                                <button class="btn btn-success add_soal"><i class="fa-solid fa-plus text-white"></i></button>
							</div>`;
               	 	return view;
				}
			}
		],
	}).columns(0).search($('select[name="select-mapel"]').val()).draw();

	/**
	 * Button pilih-pertanyaan di klik
	 * lakukan refresh table
	 */
	$('button.pilih-pertanyaan').on('click', function(e){
		e.preventDefault();
		table.columns(0).search($('select[name="select-mapel"]').val()).draw();
		table.columns(2).search($('select[name="a_jenis_pertanyaan"]').val()).draw();
	});

	/**
	 * function klik add_soal
	 */
	let nomor = 1;
	$('#tabelPilihSoal > tbody').on('click', '.btn.add_soal', e => {
        isUpdate = 1;
        const count = table.row(e.target.parentNode.closest('tr')).count(),
              item = table.row(e.target.parentNode.closest('tr')).data();

		// kondisi jika jumlah pertanyaan sudah melebihi batas
		let jumlahPertanyaan = document.querySelector('input[name="a_jumlah_petanyaan"]').value;
		let cardQuestion = document.getElementsByClassName('card-question-1');

		if(jumlahPertanyaan <= cardQuestion.length){
			return alert('Sudah melewati batas jumlah pertanyaan')
		}
		
		let content1 = document.querySelector('.content-1');

		// card untuk jawaban
		htmlContent = `<div class="card card-question-1 p-3">
				<div class="col text-end mb-2">
					<button class="btn btn-sm btn-danger delete-card"><i class="fa fa-trash text-white"></i></button>
				</div>
				<p><span>${nomor++})</span> ${item.question}</p>
				<p>A. ${item.choice_a}</p>
				<p>B. ${item.choice_b}</p>
				<p>C. ${item.choice_c}</p>
				<p>D. ${item.choice_d}</p>
			</div>
			<br>`;
		$('.content-1').append(htmlContent);

		// ketika button hapus di klik
		let btnHapus = document.getElementsByClassName('delete-card');
		for(let i=0; i < btnHapus.length; i++){
			btnHapus[i].addEventListener('click', e => {
				console.log(e)
			});
		}

    //     frm['a_id'].value = item.materi_id;
	// 			frm['a_materi_tema_title'].value = item.tema_title; 
	// 			frm['a_materi_sub_tema_title'].value = item.sub_tema_title; 
    //     frm['a_materi_title'].value = item.title; 
    //     frm['a_materi_no_urut'].value = item.no_urut; 
    //     $(mapel).selectpicker('val', item.subject_id); 
    //     frm['a_materi_subject_text'].value = item.subject_name;
    //   //  frm['a_materi_date'].value = item.available_date;
    //     frm['a_materi_note'].value = item.note;
    //     document.getElementById("preview").src = item.materi_file;


    //     $('#modal-add').modal('show');
        
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

		$('#exampleModal').modal('show');
	});

});


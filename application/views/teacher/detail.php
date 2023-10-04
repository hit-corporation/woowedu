<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/libs/sweetalert2/sweetalert2.min.css')?>" />

<?php 

$photo = base_url('assets/images/users/user.png');
if(!empty($detail['photo']) && file_exists(FCPATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'users'.DIRECTORY_SEPARATOR.$detail['photo']))
	$photo = base_url('assets/images/users/'.$detail['photo']);
?>

<style>
	#tbl-tugas td {
		max-width: 140px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	#teacher-name {
		text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.3);
	}
</style>


<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<p class="mt-4"><a href="<?=base_url()?>student" class="text-secondary">Semua Guru</a> > <span class="fw-bold">Detail</span></p>
	</div>

	<div class="container">
		<div class="row">
			<!-- profile content -->
			<div class="col-xl-34 col-lg-4 col-md-6 col-sm-12 col-xs-12 mb-3">
				<div class="card border rounded p-3 h-100">
					<div class="image-content">
						<span>
							<img id="img-profile" src="<?=$photo?>" alt="" width="50" height="50">
						</span>
						<span class="h6 ms-1 font-size-16" id="teacher-name"><?=$detail['teacher_name']?></span>
					</div>

					<dl class="row mt-3 fs-6">
						<dt class="col-1"><i class="bi bi-envelope-paper"></i></dt><dd class="col-11"><?=$detail['email']?></dd>
						<dt class="col-1"><i class="bi bi-bank"></i></dt><dd class="col-11"><?=$detail['nama_sekolah']?></dd>
					</dl>
				</div>
			</div>

			<!-- skor by date range content -->
			<div class="col-xl-8 col-lg-8 col-md-6 col-sm-12 col-xs-12 mb-3 h-100">
				<div class="card border rounded p-3 data-by-date h-100">
					<input class="border-width-1 rounded-lg ml-3"  style="height: 40px; text-align:center; border-color: rgba(0, 0, 255, 0.3);" type="text" name="daterange" 
						value="<?php 
						if(isset($start)){ 
							echo date('m/d/Y', strtotime($start));
						} else { 
							echo date('m', time()).'//1//'.date('Y', time()); 
						}?> - <?php 
						if(isset($end)){ 
							echo date('m/d/Y', strtotime($end)); 
						}else{ 
							echo date('m/d/Y', time()); 
						}?>" />

					<div class="row data-content mt-4 justify-content-center">
						<div class="col-3 total-task text-center">
							<h4>0</h4>
							<span>Total Tugas Dibuat</span>
						</div>
						<div class="col-3 total-exam text-center">
							<h4>0</h4>
							<span>Total Ujian Dibuat</span>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
				<div class="container border rounded">
					tes
				</div>
			</div> -->
		</div>
	</div>

	<!-- laporan kinerja siswa -->
	<div class="container p-4">
		<div class="row border rounded">
			<h6 class="text-center mt-4">Laporan Kinerja Siswa</h6>
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist">
					<button class="nav-link active" id="nav-ebook-tab" data-bs-toggle="tab" data-bs-target="#nav-ebook" type="button" role="tab" aria-controls="nav-ebook" aria-selected="true">Ebook</button>
					<button class="nav-link" id="nav-tugas-tab" data-bs-toggle="tab" data-bs-target="#nav-tugas" type="button" role="tab" aria-controls="nav-tugas" aria-selected="false">Tugas</button>
					<button class="nav-link" id="nav-ujian-tab" data-bs-toggle="tab" data-bs-target="#nav-ujian" type="button" role="tab" aria-controls="nav-ujian" aria-selected="false">Ujian</button>
				</div>
			</nav>
			<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">
				<div class="tab-pane fade show active" id="nav-ebook" role="tabpanel" aria-labelledby="nav-ebook-tab" tabindex="0">
					
				</div>
				<div class="tab-pane fade p-3" id="nav-tugas" role="tabpanel" aria-labelledby="nav-tugas-tab" tabindex="0">
					<div class="row">
						<div class="col-12">
							<?php 
								$bisaliat = [1, 3, 10];
								$_level = intval($_SESSION['user_level']) ?? 0;
								
								if(in_array($_level, $bisaliat)):
							?>
							<button class="btn btn-sm btn-primary text-white" data-bs-toggle="modal" data-bs-target="#modal-add" id="btn-add-tugas">Tambah</button>
							<?php endif; ?>
						</div>
					</div>
					<table class="table-rounded w-100" id="tbl-tugas" style="width: 100%">
						<thead>
							<tr>
								<th>Kelas ID</th>
								<th>Kelas</th>
								<th>Materi ID</th>
								<th>Materi</th>
								<th>Tanggal Buat</th>
								<th>Batas waktu</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tugas-body-content">

						</tbody>
					</table>

					<div class="pagination"></div>
				</div>	
				<div class="tab-pane fade p-3" id="nav-ujian" role="tabpanel" aria-labelledby="nav-ujian-tab" tabindex="0">
					<table class="table-rounded w-100" id="tbl-exam">
						<thead>
							<tr>
								<th>Kode</th>
								<th>Kelas</th>
								<th>Nama Mapel</th>
								<th>Jenis Tugas</th>
								<th>Waktu Dibuat</th>
								<th>Waktu Berakhir</th>
							</tr>
						</thead>
						<tbody id="exam-body-content">

						</tbody>
					</table>

					<div class="pagination pagination2"></div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('teacher/modal') ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Include the selectize library -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?=base_url('assets/libs/tinymce/tinymce.min.js')?>"></script>
<script src="<?=base_url('assets/libs/sweetalert2/sweetalert2.min.js')?>"></script>

<script>
	'use strict';

	var currentPage 	= 1;
	var teacher_id 		= <?=$detail['teacher_id']?>;
	let startDate 		= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
	let endDate			= moment().startOf('day').format('YYYY-MM-DD');
	const admin_url 	= document.querySelector('meta[name="admin_url"]').content;
	const modalAdd  	= document.getElementById('modal-add');
	const formTugas 	= document.forms['form-add'];
	const btnAddTugas   = document.querySelector('#btn-add-tugas');
	const selectMateri 	= document.getElementsByName('a_tugas_materi')[0];
    const selectKelas 	= document.getElementsByName('a_tugas_class')[0];
	const token 		= window.localStorage.getItem('token');
	const level			= window.atob(window.localStorage.getItem('level'));

	let isUpdate = 0;


	getSummary(teacher_id, startDate, endDate);
	
	/**
	 * @description "Ambil data materi"
	 * 
	 * @async
	 * @return {Json Object}
	 */
	const getMateri = async () => {

		try 
		{
			const url = new URL(admin_url + 'api/materi/getAll');
			const f = await fetch(url.href, {
				headers: {
					'Authorization': 'Basic ' + token
				}
			});
			const j = await f.json();
			
			return j.data;
		} 
		catch (err) 
		{
			console.log(err)
		}
	}

	/**
	 * @description "Ambil data kelas"
	 * 
	 * @async
	 * @return {Json Object}
	 */
	const getKelas = async () => {

		try 
		{
			const url = new URL(admin_url + 'api/kelas/get_all');
			const f = await fetch(url.href, {
				headers: {
					'Authorization': 'Basic ' + token
				}
			});
			const j = await f.json();
			
			return j.data;
		} 
		catch (err) 
		{
			console.log(err)
		}
	}

	/**
	 * @description "simpan data tugas"
	 * 
	 * @return {Object}
	 */
	formTugas.addEventListener('submit', e => {
		const formData = new FormData(e.target);

		$.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                var prog = document.getElementById('import-progress-1');
                xhr.upload.addEventListener('progress', e1 => {
                    if(e1.lengthComputable) {
                        prog.removeAttribute('hidden');
                        var completed = (e1.loaded === e1.total) ? 90 : Math.round((e1.loaded / e1.total) * 100);
                        prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', completed);
                        prog.getElementsByClassName('progress-bar')[0].style.width = completed + '%';
                        prog.getElementsByClassName('progress-bar')[0].innerHTML = completed + '%';
                    }
                }, false);
                xhr.addEventListener('progress', e2 => {
                    if(e2.lengthComputable) {
                        prog.removeAttribute('hidden');
                        var completed = (e2.loaded === e2.total) ? 90 : Math.round((e2.loaded / e2.total) * 100);
                        prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', completed);
                        prog.getElementsByClassName('progress-bar')[0].style.width = completed + '%';
                        prog.getElementsByClassName('progress-bar')[0].innerHTML = completed + '%';
                    }
                }, false);

                return xhr;
            },
            url: isUpdate == 1 ? admin_url + 'api/tugas/edit' : admin_url + 'api/tugas/save',
            type: 'POST',
            data: formData,
			headers: {
				'Authorization': 'Basic ' + token
			},
            contentType: false,
            processData: false,
            success(reslv) {

                var prog = document.getElementById('import-progress-1');

                prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', 100);
                prog.getElementsByClassName('progress-bar')[0].style.width = '100%';
                prog.getElementsByClassName('progress-bar')[0].innerHTML = '100%';

                var res = reslv;
                Swal.fire({
                    type: res.err_status,
                    title: '<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                    html: res.message
                });
            },
            error(err) {
                let response = JSON.parse(err.responseText);

                Swal.fire({
                    type: response.err_status,
                    title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                    html: response.message
                });
            },
            complete() {
                var prog = document.getElementById('import-progress-1');
                prog.setAttribute('hidden', 'hidden');
                tableTugas.ajax.reload();
            }
		});
	});

	/**
	 * @description "Data Table Tugas"
	 * 
	 */
	const tableTugas = $('#tbl-tugas').DataTable({
		serverSide: true,
		processing: true,
		ajax: { 
			url: admin_url + 'api/tugas/getAll',
			headers: {
				'Authorization': 'Basic ' + token
			},
			data: d => {
				d.teacher = teacher_id;
				return d;
			} 
		},
		length: 8,
		columns: [
			{
				data: 'class_id',
				visible: false
			},
			{
				data: 'class_name'
			},
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: 'title'
			},
			{
                data: 'available_date'
            },
            {
                data: 'due_date'
            },
            {
                data: null,
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">' +
                                    '<button class="btn view_tugas" style="background-color: var(--bs-purple)"><i class="bi bi-eye text-white font-size-12"></i></button>';
					if([1,3,10].includes(+level))
                    	view += '<button class="btn btn-success edit_tugas"><i class="bi bi-pen text-white font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_tugas"><i class="bi bi-trash text-white font-size-12"></i></button>';
                        view += '</div>';
                    return view;
                }
            }
		]
	});

	/**
	 * @description "Date Range Picker for Periode Tugas"
	 * 
	 */
	$('input[name="a_tugas_periode"]').daterangepicker({
			showDropdowns: true,
			minDate: 0,
			locale: {
				format: 'YYYY-MM-DD',
				separator: ' s/d '
			},
    		drops: 'up'
		}, (start, end, label) => {
			const startDate = start.format("YYYY-MM-DD HH:mm:ss"),
              	  endDate   = end.format("YYYY-MM-DD HH:mm:ss");

			formTugas['a_tugas_start'].value = startDate;
			formTugas['a_tugas_end'].value = endDate;
		});

	


	window.onload = e => (async $ => {

		const materi = [...await getMateri()].map(x => ({ id: x.materi_id, text: x.title }));
		const kelas  = [...await getKelas()].map(x => ({ id: x.class_id, text: x.class_name }));


		$(selectMateri).select2({
			theme: "bootstrap-5",
			data: materi,
			placeholder: 'Pilih Materi',
			allowClear: true,
			width: '100%'
		});

		$(selectKelas).select2({
			theme: "bootstrap-5",
			data: kelas,
			placeholder: 'Pilih Kelas',
			allowClear: true,
			width: '100%'
		});

		/**
		 * @description "Button Tambah Tugas Listener"
		 * 
		 */
		btnAddTugas.addEventListener('click', e => {
			isUpdate = 0;
			formTugas.reset();

			formTugas['a_tugas_code'].value = '';
			formTugas['a_tugas_code'].value = Math.floor(Math.random() * Date.now()).toString(36).toUpperCase();
			formTugas['a_tugas_guru'].value = teacher_id;

			$(selectMateri).val(null).trigger('change');
			$(selectKelas).val(null).trigger('change');

			const today = Date.now();
			$('input[name="a_tugas_periode"]').data('daterangepicker').setStartDate(today);
			$('input[name="a_tugas_periode"]').data('daterangepicker').setEndDate(today);

			formTugas['a_tugas_start'].value = $('input[name="a_tugas_periode"]').data('daterangepicker').startDate.format('YYYY-MM-DD HH:mm:ss');
			formTugas['a_tugas_end'].value = $('input[name="a_tugas_periode"]').data('daterangepicker').endDate.format('YYYY-MM-DD HH:mm:ss');
		});

		/**
		 * @description "View Tugas Listner"
		 * 
		 */
		$('#tbl-tugas tbody').on('click', '.btn.view_tugas', e => {
			const row = tableTugas.row(e.target.parentNode.closest('tr')).data();

			document.getElementById('title').innerHTML = row.title;
			document.getElementById('note').innerHTML = row.note;
			var link = row.task_file.replace('\\', '/');
			const downloadUrl = new URL(admin_url + '/' + link);

			document.getElementById('task_file').href = downloadUrl.href;

			$('#mdl-view-tugas').modal('show');
		});

		/**
		 * @description "Button Edit Tugas"
		 * 
		 */
		$('#tbl-tugas tbody').on('click', '.btn.edit_tugas', e => {
			isUpdate = 1;
			const row = tableTugas.row(e.target.parentNode.closest('tr')).data();
			formTugas['a_id'].value = row.task_id;
			formTugas['a_tugas_code'].value = row.code;
			formTugas['a_tugas_guru'].value = teacher_id;

			$(selectMateri).val(row.materi_id);
			$(selectKelas).val(row.class_id);

			tinymce.get('detail-tugas').setContent(row.note);
			formTugas['a_tugas_detail'].value = tinymce.get('detail-tugas').getContent();
			formTugas['a_tugas_start'].value = row.available_date;
			formTugas['a_tugas_end'].value = row.due_date;
			formTugas['a_tugas_periode'].value = row.available_date + ' - ' + row.due_date;

			// tugas
			formTugas['a_tugas_materi_text'].value = row.title;
			formTugas['a_tugas_class_text'].value = row.class_name;
			//formTugas['a_tugas_guru_text'].value = row.teacher_name;

			//document.querySelector('label[for="a_tugas_file"]').innerText = row.task_file;
			$('#modal-add').modal('show');
		});
		

		document.querySelector('#save-tugas').addEventListener('click', e => {
			const evt = new Event('submit');
			formTugas.dispatchEvent(evt);
		});

		$('input[name="a_tugas_periode"]').on('apply.daterangepicker', (e, picker) => {
			const start = picker.startDate.format("YYYY-MM-DD HH:mm:ss"),
			end   = picker.endDate.format("YYYY-MM-DD HH:mm:ss");

			formTugas['a_tugas_start'].value = start;
			formTugas['a_tugas_end'].value = end;
		});

	/**
     ===================================================
     *               DELETE DATA
     ===================================================
     */

     // DELETE ALL
     $('#delete_all').on('click', e => {
        var rows = tableTugas.rows({selected: true});
        var data = rows.data(),
            count = rows.count();
        var arr = [];
        if(count === 0) {
            Swal.fire({
				type: "error",
				title:'<h5 class="text-danger text-uppercase">Error</h5>',
				text: "No row selected !!!"
			});
			return false;
        }

        for(var i =0; i< count; i++) {
            arr.push(data[i].task_id);
        }

        Swal.fire({
            title: "Anda Yakin ?",
            text: "Data yang dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus Data",
            cancelButtonText: "Tidak, Batalkan Hapus",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(t => {
            if(t.value) {
                erase(arr, 1);
            }
        })
     });

    // DELETE ONE
    $('#tbl-tugas tbody').on('click', '.btn.delete_tugas', e => {
        var row = tableTugas.row($(e.target).parents('tr')).data();
        Swal.fire({
            title: "Anda Yakin ?",
            text: "Data yang dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus Data",
            cancelButtonText: "Tidak, Batalkan Hapus",
            closeOnConfirm: false,
            closeOnCancel: false
            }).then(t => {
                if(t.value) {
                    erase(row.task_id, 0);
                }
            })
    });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/tugas/delete',
            type: 'DELETE',
            data: JSON.stringify({data: data, isBulk: isBulk}),
            contentType: 'application/json',
			headers: {
				'Authorization': 'Basic ' + token
			},
            beforeSend(xhr, obj) {
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
                Swal.fire({
					type: resp.err_status,
					title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
					html: resp.message
				});
				//csrfToken.content = resp.token;
            },
            error(err) {
                let response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
					html: response.message
				});
				//if(response.hasOwnProperty('token'))
				//	csrfToken.setAttribute('content', response.token);
            },
            complete() {
                table.ajax.reload();
            }
        });
     }

	})(jQuery);

	tinymce.init({
        selector: '#detail-tugas',
        height: 240,
		paste_as_text: true
    });

	$(document).ready(function () {
		$('input[name="daterange"]').daterangepicker({
			opens: 'right',
			minYear: 2000,
			maxYear: 2025,
			showDropdowns: true,
			ranges: {
					'Today': [moment().startOf('day'), moment().endOf('day')],
					'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
					'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
					'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
				}
			}, function(start, end, label) {
				console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

				getSummary(teacher_id, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			}
		);

		
	});

	// FUNGSI UNTUK UBAH DATA CONTENT SUMMARY
	function getSummary(teacher_id, start, end){
		$.ajax({
			type: "POST",
			url: BASE_URL+"teacher/get_summary",
			data: {
				teacher_id, teacher_id,
				start: start,
				end: end
			},
			dataType: "JSON",
			success: function (response) {
				$('.total-exam')[0].children[0].innerHTML = response.total_exam;
				$('.total-task')[0].children[0].innerHTML = response.total_task;
			}
		});
	}

	// FUNGSI UNTUK ISI LIST DATA TUGAS
	// function getTask(page = 1, limit = 10, teacher_id){
	// 	$.ajax({
	// 		type: "GET",
	// 		url: BASE_URL+"teacher/get_task",
	// 		data: {
	// 			page: page,
	// 			limit: limit,
	// 			teacher_id: teacher_id
	// 		},
	// 		success: function (response) {
	// 			$('#tugas-body-content').html('');
	// 			$.each(response.data, function (key, value){
	// 				$('#tugas-body-content').append(`
	// 					<tr>
	// 						<td>${value.title}</td>
	// 						<td>${value.subject_name}</td>
	// 						<td>${moment(value.available_date).format('DD MMM YYYY, HH:mm')}</td>
	// 						<td>${moment(value.due_date).format('DD MMM YYYY, HH:mm')}</td>
	// 						<td><a href="${BASE_URL+`assets/files/student_task/`+value.task_file_answer}">${(value.task_file_answer != undefined) ? value.task_file_answer : ``}</a></td>
	// 						<td>${value.note.substring(0,100)}</td>
	// 					</tr>
	// 				`);
	// 			});

	// 			$('.pagination').html('');
	// 			for(let i = 0; i < response.total_pages; i++){
	// 				if(currentPage == i+1){
	// 					$('.pagination').append(`
	// 						<li class="page-item active"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
	// 					`);
	// 				}else{
	// 					$('.pagination').append(`
	// 						<li class="page-item"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
	// 					`);
	// 				}

	// 			}
	// 		}
	// 	});
	// }

	// FUNGSI UNTUK ISI LIST DATA EXAM
	function getExam(page = 1, limit = 10, teacher_id){
		$.ajax({
			type: "GET",
			url: BASE_URL+"teacher/get_exam",
			data: {
				page: page,
				limit: limit,
				teacher_id: teacher_id
			},
			success: function (response) {
				$('#exam-body-content').html('');
				$.each(response.data, function (key, value){
					$('#exam-body-content').append(`
						<tr>
							<td>${value.code}</td>
							<td>${value.class_name}</td>
							<td>${value.subject_name}</td>
							<td>${value.category_name}</td>
							<td>${moment(value.end_date).format('DD MMM YYYY, HH:mm')}</td>
							<td>${moment(value.exam_submit).format('DD MMM YYYY, HH:mm')}</td>
						</tr>
					`);
				});

				$('.pagination2').html('');
				for(let i = 0; i < response.total_pages; i++){
					if(currentPage == i+1){
						$('.pagination2').append(`
							<li class="page-item active"><a class="page-link" href="#" onclick="page2(${i+1}, event)">${i+1}</a></li>
						`);
					}else{
						$('.pagination2').append(`
							<li class="page-item"><a class="page-link" href="#" onclick="page2(${i+1}, event)">${i+1}</a></li>
						`);
					}

				}
			}
		});
	}

	// JIKA nav-tugas-tab DI KLIK
	$('#nav-tugas-tab').on('click', function(){
		tableTugas.ajax.reload();
	});

	// JIKA PAGE NUMBER DI KLIK
	// function page(pageNumber, e){
	// 	e.preventDefault();
	// 	currentPage = pageNumber;
	// 	getTask(pageNumber, 10, teacher_id);
	// }

	// JIKA PAGE NUMBER 2 DI KLIK
	function page2(pageNumber, e){
		e.preventDefault();
		currentPage = pageNumber;
		getExam(pageNumber, 10, teacher_id);
	}

	// JIKA nav-ujian-tab DI KLIK
	$('#nav-ujian-tab').on('click', function(){
		getExam(1, 10, teacher_id);
	});


</script>

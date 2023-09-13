<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<p class="mt-4"><a href="<?=base_url()?>student" class="text-secondary">Semua Siswa</a> > <span class="fw-bold">Detail Siswa</span></p>
	</div>

	<div class="container">
		<div class="row">
			<!-- profile content -->
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-3">
				<div class="card border rounded p-3 h-100">
					<div class="image-content">
						<span>
							<img src="<?=base_url('assets/images/users/').$detail['photo']?>" alt="" width="50">
						</span>
						<span><?=$detail['student_name']?></span>
					</div>

					<div class="wali mt-3 mb-3">
						<span class="fw-bold fs-6 d-block">Wali</span>
						<span class="fs-6 d-block"><?=$detail['parent_name']?></span>
					</div>

					<div class="mt-3">
						<p class="fs-6"><i class="bi bi-envelope-paper"> </i><?=$detail['email']?></p>
						<p class="fs-6"><i class="bi bi-bank"> </i><?=$detail['nama_sekolah']?></p>
					</div>
				</div>
			</div>

			<!-- skor by date range content -->
			<div class="col-xl-9 col-lg-9 col-md-6 col-sm-12 col-xs-12 mb-3 h-100">
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
						<div class="col-3 total-exam text-center">
							<h4>0</h4>
							<span>Total Tugas</span>
						</div>
						<div class="col-3 ave-exam-score text-center">
							<h4>0</h4>
							<span>Skor Rata-rata</span>
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
					<table class="table-rounded" id="tableTask" style="width: 100%;">
						<thead>
							<tr>
								<th>Id</th>
								<th>Kode</th>
								<th>Nama Tugas</th>
								<th>Ditugaskan</th>
								<th>Batas waktu</th>
								<th>Tanggal penyerahan</th>
								<th>File</th>
								<th>Notes</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="tugas-body-content">

						</tbody>
					</table>

					<div class="pagination"></div>
				</div>	
				<div class="tab-pane fade p-3" id="nav-ujian" role="tabpanel" aria-labelledby="nav-ujian-tab" tabindex="0">
					<table class="table-rounded w-100" id="tableExam">
						<thead>
							<tr>
								<th>Id</th>
								<th>Nama Mapel</th>
								<th>Jenis Tugas</th>
								<th>Total Nilai</th>
								<th>Batas Waktu</th>
								<th>Tanggal Submit</th>
								<th>Action</th>
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	var currentPage = 1;
	var student_id 	= <?=$detail['student_id']?>;
	var class_id 	= <?=$detail['class_id']?>;
	let startDate 	= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
	let endDate		= moment().startOf('day').format('YYYY-MM-DD');

	getSummary(student_id, startDate, endDate);

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

				getSummary(student_id, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			}
		);
	});

	// FUNGSI UNTUK UBAH DATA CONTENT SUMMARY
	function getSummary(student_id, start, end){
		$.ajax({
			type: "POST",
			url: BASE_URL+"student/get_summary",
			data: {
				student_id, student_id,
				start: start,
				end: end
			},
			dataType: "JSON",
			success: function (response) {
				$('.total-exam')[0].children[0].innerHTML = response.total_exam;
				$('.ave-exam-score')[0].children[0].innerHTML = response.average_exam_score;
			}
		});
	}

	// INISIALISASI TABLE TUGAS
	var tableTask = $('#tableTask').DataTable({
			// destroy: true,
			serverSide: true,
			ajax: {
				url: BASE_URL + 'student/get_task',
				method: 'GET',
				data: {
					student_id: student_id
				}
			},
			select: {
				style:	'multi',  
				selector: 'td:first-child'
			},
			columns: [
				{
					data: 'task_id',
					visible: false
				},
				{
					data: 'code',
				},
				{
					data: 'title',
				},
				{
					data: 'available_date',
					render(data, row, type, meta) {
						return moment(data).format('DD MMM YYYY, HH:mm');
					}
				},
				{
					data: 'due_date',
					render(data, row, type, meta){
						return moment(data).format('DD MMM YYYY, HH:mm');
					}
				},
				{
					data: 'task_submit',
					class: 'text-center',
					render(data, row, type, meta){
						return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<span class="text-white bg-danger p-1 rounded">Belum Dikerjakan</span>`;
					}
				},
				{
					data: 'task_file_answer',
					class: 'text-center',
					render(data, row, type, meta){
						return (data) ? `<a href="${BASE_URL+'assets/files/student_task/'+class_id+'/'+data}"><i class="bi bi-file-text-fill fs-20"></i></a>` : `-`;
					}
				},
				{
					data: 'task_note',
					class: 'text-center',
					render(data, row, type, meta){
						return (data) ? data.substring(0, 100) : `-`;
					}
				},
				{
					data: null,
					class: 'text-center',
					render(data, type, row, meta) {
						let view = `<div class="btn-group btn-group-sm float-right">
										<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-success edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></a>
									</div>`;
						let endDt = new Date(row.due_date);	
						let now = new Date();
						
						if(endDt < now) view = ''; 
						return view;
					}
				}
			]
		});

	// INISIALISASI TABLE EXAM
	var tableExam = $('#tableExam').DataTable({
		serverSide: true,
		ajax: {
			url: BASE_URL + 'student/get_exam',
			method: 'GET',
			data: {
				student_id: student_id
			}
		},
		select: {
			style: 'multi',
			selector: 'td:first-child',
		},
		columns: [
			{
				data: 'exam_id',
				visible: false
			},
			{
				data: 'subject_name',
			},
			{
				data: 'category_name',
			},
			{
				data: 'exam_total_nilai',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? `<b>${data}</b>` : `-`;
				}
			},
			{
				data: 'end_date',
				class: 'text-center',
				render(data, row, type, meta){
					return moment(data).format('DD MMM YYYY, HH:mm');
				}
			},
			{
				data: 'exam_submit',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<span class="text-white bg-danger p-1 rounded">Belum Dikerjakan</span>`;
				}
			},
			{
				data: null,
				class: 'text-center',
				render(data, type, row, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
									<button class="btn btn-success edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></button>
								</div>`;
					return view;
				}
			}
		]
	});
</script>

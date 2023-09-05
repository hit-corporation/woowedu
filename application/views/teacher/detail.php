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
						<span><?=$detail['teacher_name']?></span>
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
					<table class="table-rounded">
						<thead>
							<tr>
								<th>Nama Tugas</th>
								<th>Ditugaskan</th>
								<th>Batas waktu</th>
								<th>Tanggal penyerahan</th>
								<th>File</th>
								<th>Notes</th>
							</tr>
						</thead>
						<tbody id="tugas-body-content">

						</tbody>
					</table>

					<div class="pagination"></div>
				</div>	
				<div class="tab-pane fade p-3" id="nav-ujian" role="tabpanel" aria-labelledby="nav-ujian-tab" tabindex="0">
					<table class="table-rounded w-100">
						<thead>
							<tr>
								<th>Nama Mapel</th>
								<th>Jenis Tugas</th>
								<th>Total Nilai</th>
								<th>Batas Waktu</th>
								<th>Tanggal Submit</th>
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

<script>
	var currentPage = 1;
	var student_id 	= <?=$detail['teacher_id']?>;
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
			url: BASE_URL+"teacher/get_summary",
			data: {
				student_id, student_id,
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
	function getTask(page = 1, limit = 10, student_id){
		$.ajax({
			type: "GET",
			url: BASE_URL+"student/get_task",
			data: {
				page: page,
				limit: limit,
				student_id: student_id
			},
			success: function (response) {
				$('#tugas-body-content').html('');
				$.each(response.data, function (key, value){
					$('#tugas-body-content').append(`
						<tr>
							<td>${value.title}</td>
							<td>${value.available_date}</td>
							<td>${value.due_date}</td>
							<td>${value.task_submit}</td>
							<td><a href="${BASE_URL+`assets/files/student_task/`+value.task_file_answer}">${value.task_file_answer}</a></td>
							<td>${value.note}</td>
						</tr>
					`);
				});

				$('.pagination').html('');
				for(let i = 0; i < response.total_pages; i++){
					if(currentPage == i+1){
						$('.pagination').append(`
							<li class="page-item active"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
						`);
					}else{
						$('.pagination').append(`
							<li class="page-item"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
						`);
					}

				}
			}
		});
	}

	// FUNGSI UNTUK ISI LIST DATA EXAM
	function getExam(page = 1, limit = 10, student_id){
		$.ajax({
			type: "GET",
			url: BASE_URL+"student/get_exam",
			data: {
				page: page,
				limit: limit,
				student_id: student_id
			},
			success: function (response) {
				$('#exam-body-content').html('');
				$.each(response.data, function (key, value){
					$('#exam-body-content').append(`
						<tr>
							<td>${value.subject_name}</td>
							<td>${value.category_name}</td>
							<td>${value.exam_total_nilai}</td>
							<td>${value.end_date}</td>
							<td>${value.exam_submit}</td>
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
		getTask(1, 10, student_id);
	});

	// JIKA PAGE NUMBER DI KLIK
	function page(pageNumber, e){
		e.preventDefault();
		currentPage = pageNumber;
		getTask(pageNumber, 10, student_id);
	}

	// JIKA PAGE NUMBER 2 DI KLIK
	function page2(pageNumber, e){
		e.preventDefault();
		currentPage = pageNumber;
		getExam(pageNumber, 10, student_id);
	}

	// JIKA nav-ujian-tab DI KLIK
	$('#nav-ujian-tab').on('click', function(){
		getExam(1, 10, student_id);
	});
</script>

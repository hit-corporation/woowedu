<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<h4>Semua Guru</h4>
		<h6 class="mt-4">Ringkasan</h6>

		<div class="container border rounded p-4">
			<div class="row">	
				<div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12">
					<div class="mb-3 circle-stat-card">
						<div class="d-flex flex-column" >
							<div class="row justify-content-around my-auto border-right">
								<div class="col-1 d-flex flex-column align-items-center">
									<span class="stat-circle mb-3">
										<span class="stat-number"></span>
									</span>
									<span>Jumlah Guru Aktif</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-xs-12">
					<!-- chart by date range content -->
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
								<canvas id="myChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="row mt-3">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<select class="pilih-kelas form-control" name="pilih-kelas">
					<option value="">Semua Kelas</option>
				</select>
			</div>
		</div> -->

		<div class="container border rounded p-4 mt-3">
			<h6 class="total-data"></h6>

			<div class="row">
				<div class="col-xl-3 col-lg-4 col-md-5 col-sm-7 col-xs-8">
					<div class="input-group mb-3">
						<input type="text" class="form-control" placeholder="Pencarian siswa" aria-label="Pencarian siswa" aria-describedby="basic-addon2" name="nama-siswa">
						<div class="input-group-append">
							<button id="search" class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
						</div>
					</div>
				</div>

				<div class="col-xl-9 col-lg-8 col-md-7 col-sm-5 col-xs-4" style="text-align: right;">
					<button class="btn btn-success" id="download">Download</button>
				</div>
			</div>

			<div class="container mt-3 p-0">
				<table class="table table-bordered table-hover w-100">
					<thead class="text-white" style="background-color: #80d0c7;">
						<tr>
							<th>Nama Siswa</th>
							<th>Email</th>
							<th>Status Terkini</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody id="table-body-content"></tbody>
				</table>

				<div class="pagination mt-3"></div>
			</div>

		</div>

	</div>
</section>

<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="<?=base_url('assets/js/jquery.redirect.js')?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
	$(document).ready(function() {
		// ISI DATA PILIH KELAS
		// $.get(BASE_URL+'teacher/get_class', function(data){
		// 	$.each(data, function (i, val) { 
		// 		 $('select[name="pilih-kelas"]').append(`<option value="${val.class_id}">${val.class_name}</option>`);
		// 	});

		// });

		// $('.pilih-kelas').select2();

		// ISI DATA TOTAL GURU AKTIF
		$.get(BASE_URL+'teacher/get_total', function(data){
			$('.stat-number').text(data.total_row);
		});

		// INPUT DATE RANGE
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
				// console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
				$('#myChart').html('');
				getLineChart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			}
		);
		
	});

	let startDate 	= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
	let endDate		= moment().startOf('day').format('YYYY-MM-DD');

	// FUNGSI UNTUK UBAH DATA LINE CHART
	function getLineChart(start, end){
		$.ajax({
			type: "POST",
			url: BASE_URL+"teacher/get_task_chart",
			data: {
				start: start,
				end: end
			},
			dataType: "JSON",
			success: function (response) {
				drawLineChart(response);
			}
		});
	}
	

	var currentPage = 1;
	load_data(1,10);

	// KETIKA BUTTON CARI DI KLIK
	$('#search').on('click', function(e){
		load_data();
	});

	// create function load data
	function load_data(page = 1, limit = 10){
		let namaGuru = $('input[name="nama-siswa"]').val();

		$.ajax({
			type: "GET",
			url: BASE_URL+"teacher/search",
			data: {
				page: page,
				limit: limit,
				filter: {
					namaGuru: namaGuru
				}
			},
			success: function (response) {
				$('.total-data').text(response.total_records + ' Guru');

				$('#table-body-content').html('');
				$.each(response.data, function (key, value){
					$('#table-body-content').append(`
						<tr class="bg-clear">
							<td>${value.teacher_name}</td>
							<td>${(value.email != null) ? value.email : ''}</td>
							<td>${(value.status == 1) ? 'aktif' : 'tidak aktif'}</td>
							<td class="btn-eye"><a href="${BASE_URL+'teacher/detail/'+value.teacher_id}"><i class="bi bi-eye"></i></a></td>
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

	// JIKA PAGE NUMBER DI KLIK
	function page(pageNumber, e){
		e.preventDefault();
		currentPage = pageNumber;
		load_data(pageNumber);
	}

	// DOWNLOAD DATA STUDENT
	$('#download').click(function (e) { 
		e.preventDefault();

		$.redirect(BASE_URL+"teacher/download",
			{
				nama: $('input[name="nama-siswa"]').val()
			},
		"POST", "_blank");
	});
</script>

<!-- CHART MURID PER KELAS -->
<script>
	function drawLineChart(data){
		const ctx = document.getElementById('myChart');
		let dataChart = data;
	
		let labels 		= [];
		let jumlahTask = [];
	
		$.each(dataChart, function (i, val) { 
			 labels.push(val.tanggal);
			 jumlahTask.push(val.value);
		});
	
		new Chart(ctx, {
			type: 'line',
			data: {
				// labels: ['1.1', '1.2', '2.1', '2.2', '3.1', '3.2'],
				labels: labels,
				datasets: [
						{
							label: 'Kelas',
							// data: [35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25],
							data: jumlahTask,
							fill: false,
							borderColor: 'rgb(75, 192, 192)',
							tension: 0.1
						}
					]
			},
			options: {
				scales: {
				y: {
					beginAtZero: true
				}
				}
			}
		});
	}
</script>

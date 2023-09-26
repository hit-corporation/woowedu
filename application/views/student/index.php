<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
	#table-siswa_info{display: none;}
</style>

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<h4>Semua Siswa</h4>
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
									<span>Jumlah Siswa</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-xs-12">
					<div>
						<canvas id="myChart"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<select class="pilih-kelas form-control" name="pilih-kelas">
					<option value="">Semua Kelas</option>
				</select>
			</div>
		</div>

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
				<table class="table table-striped" id="table-siswa">
					<thead>
						<tr>
							<th>Id</th>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?=base_url('assets/js/jquery.redirect.js')?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function() {
		// ISI DATA PILIH KELAS
		$.get(BASE_URL+'student/get_class', function(data){
			$.each(data, function (i, val) { 
				 $('select[name="pilih-kelas"]').append(`<option value="${val.class_id}">${val.class_name}</option>`);
			});

		});

		$('.pilih-kelas').select2();

		// ISI DATA TOTAL SISWA
		$.get(BASE_URL+'student/get_total', function(data){
			$('.stat-number').text(data.total_row);
		});
	});

	// var currentPage = 1;
	// load_data(1,10);

	// KETIKA BUTTON CARI DI KLIK
	// $('#search').on('click', function(e){
	// 	load_data();
	// });

	// create function load data
	// function load_data(page = 1, limit = 10){
	// 	let kelas = $('select[name="pilih-kelas"]').val();
	// 	let namaSiswa = $('input[name="nama-siswa"]').val();

	// 	$.ajax({
	// 		type: "GET",
	// 		url: BASE_URL+"student/search",
	// 		data: {
	// 			page: page,
	// 			limit: limit,
	// 			filter: {
	// 				kelas: kelas,
	// 				namaSiswa: namaSiswa
	// 			}
	// 		},
	// 		success: function (response) {
	// 			$('.total-data').text(response.total_records + ' Siswa');

	// 			$('#table-body-content').html('');
	// 			$.each(response.students, function (key, value){
	// 				$('#table-body-content').append(`
	// 					<tr class="bg-clear">
	// 						<td>${value.student_name}</td>
	// 						<td>${(value.email != null) ? value.email : ''}</td>
	// 						<td>${(value.ta_aktif == 1) ? 'aktif' : 'tidak aktif'}</td>
	// 						<td class="btn-eye"><a href="${BASE_URL+'student/detail/'+value.student_id}"><i class="bi bi-eye"></i></a></td>
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

	// JIKA PAGE NUMBER DI KLIK
	// function page(pageNumber, e){
	// 	e.preventDefault();
	// 	currentPage = pageNumber;
	// 	load_data(pageNumber);
	// }

	// DOWNLOAD DATA STUDENT
	$('#download').click(function (e) { 
		e.preventDefault();

		$.redirect(BASE_URL+"student/download",
			{
				kelas: $('select[name="pilih-kelas"]').val(),
				nama: $('input[name="nama-siswa"]').val()
			},
		"POST", "_blank");
	});
</script>

<!-- CHART MURID PER KELAS -->
<script>
	const ctx = document.getElementById('myChart');
	let data = <?=json_encode($kelas)?>;

	let labels 		= [];
	let jumlahMurid = [];

	$.each(data, function (i, val) { 
		 labels.push(val.class_name);
		 jumlahMurid.push(val.value);
	});

	new Chart(ctx, {
		type: 'bar',
		data: {
			// labels: ['1.1', '1.2', '2.1', '2.2', '3.1', '3.2'],
			labels: labels,
			datasets: [
					{
						label: 'Kelas',
						// data: [35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25],
						data: jumlahMurid,
						backgroundColor: [
							'rgba(255, 99, 132, 0.9)',
							'rgba(255, 159, 64, 0.9)',
							'rgba(255, 205, 86, 0.9)',
							'rgba(75, 192, 192, 0.9)',
							'rgba(54, 162, 235, 0.9)',
							'rgba(153, 102, 255, 0.9)',
							'rgba(201, 203, 207, 0.9)',
							'rgba(255, 99, 132, 0.9)',
							'rgba(255, 159, 64, 0.9)',
							'rgba(255, 205, 86, 0.9)',
							'rgba(75, 192, 192, 0.9)',
							'rgba(54, 162, 235, 0.9)',
							'rgba(153, 102, 255, 0.9)',
							'rgba(201, 203, 207, 0.9)'
						],
						borderWidth: 1
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
</script>

<script>
	// ######################################### DATA TABLE SISWA #########################################

	// INISIALISASI TABLE TUGAS
	var tableSiswa = $('#table-siswa').DataTable({
		serverSide: true,
		ajax: {
			url: BASE_URL + 'student/search',
			method: 'GET',
			data: {}
		},
		select: {
			style: 'multi',
			selector: 'td:first-child'
		},
		columns: [
			{
				data: 'student_id',
				visible: false
			},{
				data: 'student_name'
			},{
				data: 'email'
			},{
				data: 'ta_aktif',
				class: 'text-center',
				render(data, type, row, meta){
					let status = (data == 1) ? 'Aktif' : 'Tidak Aktif';
					return status;
				}
			},{
				data: null,
				class: 'text-center',
				render(data, type, row, meta){
					return `<a href="${BASE_URL+'student/detail/'+row.student_id}"><i class="bi bi-eye"></i></a>`;
				}
			}
		]
	});

	/**
	 * ************************************
	 *          SEARCH / FILTER
	 * ************************************
	 */

	$('#search').on('click', function(){
		tableSiswa.columns(0).search($('input[name="nama-siswa"]').val()).draw();
	});

	$('select[name="pilih-kelas"]').on('change', function(e){
		tableSiswa.columns(1).search(e.currentTarget.value).draw();
	});

</script>

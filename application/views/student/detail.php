<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<?php 
	$user_level = $this->session->userdata('user_level');
?>

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<?php if($user_level == 3 || $user_level == 6): ?>
		<p class="mt-4"><a href="<?=base_url()?>student" class="text-secondary">Semua Siswa</a> > <span class="fw-bold">Detail Siswa</span></p>
		<?php endif ?>
	</div>

	<div class="container">
		<div class="row">
			<!-- profile content -->
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-1" style="height: 450px;">
				<div class="card border rounded-4 p-3 h-100">
					<div class="image-content text-center mt-3">
						<span>
							<img src="<?=base_url('assets/images/users/').$detail['photo']?>" alt="" width="100">
						</span>
						<p class="mt-2"><?=$detail['student_name']?></p>
					</div>

					<div class="wali mt-2 mb-2">
						<span class="fw-bold fs-6 d-block">Wali</span>
						<span class="fs-6 d-block"><?=$detail['parent_name']?></span>
					</div>

					<div class="mt-2">
						<p class="fs-6"><i class="bi bi-book"> </i><?=$detail['class_name']?></p>
						<p class="fs-6"><i class="bi bi-envelope-paper"> </i><?=$detail['email']?></p>
						<p class="fs-6"><i class="bi bi-bank"> </i><?=$detail['nama_sekolah']?></p>
					</div>
				</div>
			</div>

			<!-- skor by date range content -->
			<div class="col-xl-9 col-lg-9 col-md-6 col-sm-12 col-xs-12 mb-1" style="height: 450px;">
				<div class="card border rounded-4 p-3 data-by-date h-100">
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
					<button class="nav-link active" id="nav-tugas-tab" data-bs-toggle="tab" data-bs-target="#nav-tugas" type="button" role="tab" aria-controls="nav-tugas" aria-selected="false">Tugas</button>
					<button class="nav-link" id="nav-ujian-tab" data-bs-toggle="tab" data-bs-target="#nav-ujian" type="button" role="tab" aria-controls="nav-ujian" aria-selected="false">Ujian</button>
					<button class="nav-link" id="nav-ebook-tab" data-bs-toggle="tab" data-bs-target="#nav-ebook" type="button" role="tab" aria-controls="nav-ebook" aria-selected="true">Ebook</button>
					<button class="nav-link" id="nav-sesi-tab" data-bs-toggle="tab" data-bs-target="#nav-sesi" type="button" role="tab" aria-controls="nav-sesi" aria-selected="true">Sesi</button>
				</div>
			</nav>
			<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">
				<div class="tab-pane fade show active p-3" id="nav-tugas" role="tabpanel" aria-labelledby="nav-tugas-tab" tabindex="0">
					<table class="table-rounded" id="tableTask" style="width: 100%;">
						<thead>
							<tr>
								<th>Id</th>
								<th>Kode</th>
								<th>Mata Pelajaran</th>
								<th>Nama Tugas</th>
								<th>Guru</th>
								<th>Ditugaskan</th>
								<th>Batas waktu</th>
								<th>Tanggal penyerahan</th>
								<th>File</th>
								<th>Notes</th>
								<th>Nilai</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade p-3" id="nav-ujian" role="tabpanel" aria-labelledby="nav-ujian-tab" tabindex="0">
					<table class="table-rounded w-100" id="tableExam">
						<thead>
							<tr>
								<th>Id</th>
								<th>Nama Mapel</th>
								<th>Jenis Tugas</th>
								<th>Guru</th>
								<th>Total Nilai</th>
								<th>Batas Waktu</th>
								<th>Tanggal Submit</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade" id="nav-ebook" role="tabpanel" aria-labelledby="nav-ebook-tab" tabindex="0">
					<div class="container px-5">
						<table class="table-rounded w-100" id="tableBookHistory">
							<thead>
								<tr>
									<th>Id</th>
									<th>Cover</th>
									<th>Terakhir dibaca</th>
									<th>Kode Buku</th>
									<th>Title</th>
									<th>Kategori</th>
									<th>Pengarang</th>
									<th>Tahun</th>
									<th>Deskripsi</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				
				<div class="tab-pane fade" id="nav-sesi" role="tabpanel" aria-labelledby="nav-sesi-tab" tabindex="0">
					<div class="container px-5">
						<div class="row">
							
							<div class="col-8">
								<div id="calendar" class="col"></div>
							</div>
							<div class="col-4" id="sesi_content"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- MODAL VIEW BOOK -->
<div id="modal-show" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">DETAIL BUKU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <img data-item="cover_img" class="img-fluid" src="" alt="">
                    </div>
                    <aside class="col-12 col-lg-9">
                        <h3 class="mb-4 text-dark" data-item="title"></h3>
                        <dl class="row">
                            <dt class="col-4 text-primary">
                                Kode Buku
                            </dt>
							<dd class="col-8 mb-1">
                                :&nbsp;<span data-item="book_code"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penulis
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="author"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penerbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publisher_name"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Tahun Terbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publish_year"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                ISBN
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="isbn"></span>
                            </dd>
							<dt class="col-4">
                                
                            </dt>
                             <dd class="col-8 mb-1 mt-3">
                                <a href="" class="read-link btn btn-primary d-inline text-white">Baca</a>
                            </dd>

							
                        </dl>
                        
                    </aside>
                    <span class="col-12">
                        <hr class="my-3" />
                        <h6 class="font-weight-bold text-primary">Deskripsi</h6>
                        <p data-item="description" class="text-justify font-weight-light text-dark fs-14"></p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<script type="text/javascript" src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->
<script type="text/javascript" src="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.js')?>"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.css')?>" />

<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
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

		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {
			height:500,
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'listDay,listWeek'
			},

			// customize the button names,
			// otherwise they'd all just say "list"
			views: {
				listDay: { buttonText: 'list day' },
				listWeek: { buttonText: 'list week' },
			},

			initialView: 'listWeek',
			initialDate: '<?=date('Y-m-d')?>',
			navLinks: true, // can click day/week names to navigate views
			editable: true,
			dayMaxEvents: true, // allow "more" link when too many events
			events: {
					url: '<?php echo base_url(); ?>student/sesi_load_data',
					error: function() {
						$('#script-warning').show();
					}
				},
			eventDidMount: function(info) {
					let title = info.el.children[2].innerText;
					let teacher = info.event._def.extendedProps.teacher;
					let subject_name = info.event._def.extendedProps.subject_name;
					info.el.children[2].innerHTML = (`<p class="fs-14">${title}</p>
						<p class="text-success fw-bold fs-14">Guru: ${teacher}</p>
						<span>Mata Pelajaran: <span style="background-color: #3989d9; color: white; padding-left: 5px; padding-right: 5px; border-radius: 10px; box-shadow: 3px 3px 5px lightblue;">${subject_name}</span></span>`);
				},
			eventClick: function(info) {
					var eventObj = info.event;
					$('#sesi_content').load('<?php echo base_url(); ?>sesi/sesidetail/'+eventObj.id);
				},
			loading: function(bool) {
					$('#loading').toggle(bool);
				}
		});

		calendar.render();
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
					data: 'subject_name',
				},
				{
					data: 'title',
				},
				{
					data: 'teacher_name',
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
					data: 'score',
					class: 'text-center',
					render(data, row, type, meta){
						return `<b>${(data) ? data : '-'}</b>`;
					}
				},
				{
					data: null,
					class: 'text-center',
					render(data, type, row, meta) {
						let btnEdit = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-success edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></a>`;
						let btnView = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-primary view_subject rounded-5"><i class="bi bi-eye text-white"></i></a>`;
						
						let endDt = new Date(row.due_date);	
						let now = new Date();
						
						let container = `<div class="btn-group btn-group-sm float-right">
										${(endDt < now) ? '' : btnEdit}
										${(row.task_submit) ? btnView : ''}
									</div>`;
						return container;
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
				data: 'teacher_name',
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
	// INISIALISASI TABLE HISTORY BOOK
	var tableBookHistory = $('#tableBookHistory').DataTable({
		serverSide: true,
		ajax:{
			url: BASE_URL + 'student/get_history_book',
			method: 'GET',
			data: {
				student_id: student_id
			}
		},
		select: {
			style: 'multi',
			selector: 'td:first-child'
		},
		columns: [
			{
				data: 'book_id',
				visible: false
			},
			{
				data: 'cover_img',
				class: 'text-center',
				render(data, type, row, meta){
					return `<img src="${data}" alt="" width="100">`;
				}
			},
			{
				data: 'max',
				render(data, type, row, meta){
					let tanggal = moment(data).format('DD MMM YYYY HH:mm');
					return tanggal;
				}
			},
			{
				data: 'book_code'
			},
			{
				data: 'title'
			},
			{
				data: 'category_name'
			},
			{
				data: 'author'
			},
			{
				data: 'publish_year'
			},
			{
				data: 'description',
				render(data, type, row, meta){
					return data.substring(0,100)+' ...';
				}
			},
			{
				data: null,
				class: 'text-center',
				render(data, type, row, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
									<button class="btn btn-success edit_subject rounded-5" onclick="showModalBook(${row.book_id})"><i class="bi bi-eye text-white"></i></button>
								</div>`;
					return view;
				}
			}
		]
	});

	function showModalBook(book_id){
		$.ajax({
			type: "GET",
			url: BASE_URL+"student/book_detail",
			data: {
				book_id: book_id
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					let data = response.data;
					$('#modal-show').modal('show');
					$('[data-item="cover_img"]')[0].src = data.cover_img;
					$('[data-item="book_code"]')[0].innerHTML = data.book_code;
					$('[data-item="author"]')[0].innerHTML = data.author;
					$('[data-item="publisher_name"]')[0].innerHTML = data.publisher_name;
					$('[data-item="publish_year"]')[0].innerHTML = data.publish_year;
					$('[data-item="isbn"]')[0].innerHTML = data.isbn;
					$('[data-item="description"]')[0].innerHTML = data.description;
					$('.read-link')[0].href = BASE_URL+'ebook/open_book?id='+book_id;
				}

			}
		});
	}
</script>

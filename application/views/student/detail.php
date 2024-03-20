<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.css')?>" />

<style>
	.fc-event-title {
		inline-size: 300px;
		overflow-wrap: break-word;
	}

	.fc-daygrid-dot-event .fc-event-title{
		overflow-x: auto !important;
	}
</style>

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
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-1 card-foto-siswa">
				<div class="card border rounded-4 p-3 h-100">
					<div class="image-content text-center mt-3">
						<span>
							<img src="<?=base_url('assets/images/users/').$detail['photo']?>" alt="" width="100">
						</span>
						<p class="mt-2"><?=$detail['student_name']?></p>
						<input type="hidden" id="student_id" value="<?=$detail['student_id']?>">
						<input type="hidden" id="class_id" value="<?=$detail['class_id']?>">
					</div>

					<div class="wali mt-2 mb-2 text-center">
						<span class="fw-bold fs-6 d-block">Wali Murid</span>
						<span class="fs-6 d-block"><?=$detail['parent_name']?></span>
					</div>

					<div class="mt-3">
						<!-- <p class="fs-6"><i class="bi bi-book"> </i><?=$detail['class_name']?></p>
						<p class="fs-6"><i class="bi bi-envelope-paper"> </i><?=$detail['email']?></p>
						<p class="fs-6"><i class="bi bi-bank"> </i><?=$detail['nama_sekolah']?></p> -->
					</div>
				</div>
			</div>

			<!-- skor by date range content -->
			<div class="col-xl-9 col-lg-9 col-md-6 col-sm-12 col-xs-12 mb-1 card-filter-tugas">
				<div class="card border rounded-4 p-3 data-by-date h-100">

					<div class="row mt-3 mx-3">
						<div class="col-6">
							<p class="fs-6"><i class="bi bi-book"> </i><?=$detail['class_name']?></p>
						</div>
						<div class="col-6">
							<p class="fs-6"><i class="bi bi-envelope-paper"> </i><?=$detail['email']?></p>
						</div>
						<div class="col-6">
							<p class="fs-6"><i class="bi bi-bank"> </i><?=$detail['nama_sekolah']?></p>
						</div>
					</div>

					<input class="border-width-1 rounded-lg mx-3"  style="height: 40px; text-align:center; border-color: rgba(0, 0, 255, 0.3);" type="text" name="daterange" 
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
						<div class="col-6 total-task text-center">
							<h4>0</h4>
							<span>Total Tugas yang di berikan oleh guru</span>
						</div>
						<div class="col-6 total-task-submit text-center">
							<h4>0</h4>
							<span>Total Tugas yang sudah di kerjakan</span>
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
					<button class="nav-link active" id="nav-sesi-tab" data-bs-toggle="tab" data-bs-target="#nav-sesi" type="button" role="tab" aria-controls="nav-sesi" aria-selected="true"><i class="fa-regular fa-calendar-days h6"></i> Sesi</button>
					<button class="nav-link" id="nav-materi-guru-tab" data-bs-toggle="tab" data-bs-target="#nav-materi-guru" type="button" role="tab" aria-controls="nav-materi-guru" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> Materi Guru</button>
					<button class="nav-link" id="nav-tugas-tab" data-bs-toggle="tab" data-bs-target="#nav-tugas" type="button" role="tab" aria-controls="nav-tugas" aria-selected="false"><i class="fa-solid fa-pen-clip h6"></i> Tugas</button>
					<button class="nav-link" id="nav-ujian-tab" data-bs-toggle="tab" data-bs-target="#nav-ujian" type="button" role="tab" aria-controls="nav-ujian" aria-selected="false"><i class="fa-solid fa-list-check h6"></i> Ujian</button>
					<button class="nav-link" id="nav-ebook-tab" data-bs-toggle="tab" data-bs-target="#nav-ebook" type="button" role="tab" aria-controls="nav-ebook" aria-selected="false"><i class="fa-solid fa-book-bookmark h6"></i> Ebook</button>
				</div>
			</nav>
			<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">
				<div class="tab-pane fade p-3" id="nav-materi-guru" role="tabpanel" aria-labelledby="nav-materi-guru-tab" tabindex="0">
					<table class="table-rounded" id="tableMateriGuru" style="width: 100%;">
						<thead>
							<tr>
								<th>Nama Matapelajaran</th>
								<th>Nama Materi</th>
								<th>Ditugaskan</th>
								<th>Ukuran</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="tab-pane fade p-3" id="nav-tugas" role="tabpanel" aria-labelledby="nav-tugas-tab" tabindex="0">
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
				
				<div class="tab-pane fade show active" id="nav-sesi" role="tabpanel" aria-labelledby="nav-sesi-tab" tabindex="0">
					<div class="container p-3 border rounded" style="height: 100%;">
						<!-- <div class="row"> -->
							
							<!-- <div class="col-8"> -->
								<div id="calendar" class="mb-3"></div>
							<!-- </div> -->
							<!-- <div class="col-4" id="sesi_content"></div> -->
						<!-- </div> -->
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

<script type="text/javascript" src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/node_modules/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url('assets/js/student_detail.js')?>"></script>

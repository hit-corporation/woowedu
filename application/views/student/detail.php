<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<p class="mt-4"><a href="<?=base_url()?>student" class="text-secondary">Semua Siswa</a> > <span class="fw-bold">Detail Siswa</span></p>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-3">
				<div class="container border rounded p-3">
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
			<div class="col-xl-5 col-lg-9 col-md-6 col-sm-12 col-xs-12 mb-3 h-100">
				<div class="container border rounded p-3 data-by-date">
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
				</div>
			</div>
			<div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
				<div class="container border rounded">
					tes
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
			}
		);
	});
</script>

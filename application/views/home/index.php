<!-- Chart Resources -->
<script src="//cdn.amcharts.com/lib/5/index.js"></script>
<script src="//cdn.amcharts.com/lib/5/xy.js"></script>
<script src="//cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

<style>
	#muridPerKelas { width: 100%; height: 250px; }
	#guruChart { width: 100%; height: 250px; }
	#loginBulananChart { width: 100%; height: 350px; }
</style>

<?php $user_level = $this->session->userdata('user_level'); ?>
<section class="explore-section section-padding" id="section_2">

	<!-- SECTION CHART UNTUK KEPSEK -->
		<div class="container mt-5 <?=($user_level != 6) ? 'd-none' : ''?>">
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-2 text-center">
					<div class="container border rounded shadow-sm p-3">
						<span class="mb-2">Murid perkelas</span>
						<canvas id="muridPerKelas"></canvas>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-2 text-center">
					<div class="container border rounded shadow-sm p-3">
						<span class="mb-2">Guru</span>
						<div id="guruChart"></div>
					</div>
				</div>

				<!-- SEMENTARA DI HIDE DULU -->
				<div class="col-12 p-2 text-center d-none">
					<div class="container border rounded shadow-sm p-3">
						<span class="mb-2">Total Login bulanan</span>
						<div id="loginBulananChart"></div>
					</div>
				</div>
			</div>
		</div>
	

	<div class="container mt-5">

			<div class="col-12 text-center">
				<h2 class="mb-4">Pengumuman</h1>
			</div>

		</div>
	</div>

	

	<div class="container-fluid">
		<div class="row">
			<ul class="nav nav-tabs" id="myTab" role="tablist">

				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="papan-pengumuman-tab" data-bs-toggle="tab" data-bs-target="#papan-pengumuman-tab-pane" type="button" role="tab" aria-controls="papan-pengumuman-tab-pane" aria-selected="true">
						<i class="bi bi-clipboard-check-fill me-2"></i>Papan Pengumuman
					</button>
				</li>

				<?php if($user_level == 4 || $user_level == 5) : ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas-tab-pane" type="button" role="tab" aria-controls="tugas-tab-pane" aria-selected="true">
							<i class="bi bi-pen-fill me-2"></i>Tugas
						</button>
					</li>
				<?php endif ?>

			</ul>
		</div>
	</div>

	<div class="container">
		<div class="row">

			<div class="col-12">
				<div class="tab-content" id="myTabContent">

					<!-- TAB PENGUMUMAN -->
					<div class="tab-pane fade show active" id="papan-pengumuman-tab-pane" role="tabpanel" aria-labelledby="papan-pengumuman-tab" tabindex="0">   
							
						<div class="row">
							<?php foreach($news as $key => $val) : ?>
								<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3">
									<div class="custom-block custom-block-overlay">
										<div class="d-flex flex-column h-100">
											<a href="<?=base_url('news/detail/').$val['id']?>">
												<div class="custom-block-overlay-text d-flex">
													<div>
														<h5 class="text-white mb-2 title-news-card"><?=$val['judul']?></h5>
														<span class="date-news-card"><?=date('d M Y H:i', strtotime($val['tanggal']))?></span>
														<p class="text-white content-news-card"><?=substr(strip_tags($val['isi']), 0, 100) . ' ...'?></p>
														
														
													</div>
												</div>
											</a>
											<div class="section-overlay"></div>
										</div>
									</div>
								</div>
							<?php endforeach ?>
						</div>

						
					</div>


					<!-- TASK CONTENT -->
					<div class="tab-pane fade" id="tugas-tab-pane" role="tabpanel" aria-labelledby="tugas-tab" tabindex="0">
						<div class="row d-flex justify-content-center">

							<?php  if(isset($tasks)) : ?>
								<?php foreach ($tasks as $key => $value) : ?>
								<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12  mb-4">
									<div class="custom-block bg-white shadow-lg">
										<a href="<?=base_url('task/detail/').$value['task_id']?>">
											<div class="d-flex">
												<div>
													<h5 class="mb-2"><?=$value['subject_name']?></h5>
													<p class="fs-12">Guru: <?=$value['teacher_name']?></p>
													<p><?=$value['note']?></p>
													<span class="text-white fs-14 mt-4 bg-warning d-inline rounded p-1 mt-3 d-inline-block">batas akhir: <?= date('d M Y H:i', strtotime($value['due_date'])) ?></span>
												</div>
											</div>
										</a>
									</div>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>

						</div>
					</div>

					
				</div>

			</div>
		</div>
	</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
	$(document).ready(function () {
		let userLevel = <?=$user_level?>;
		if(userLevel == 6){
			guruChart();
		}
	});
</script>

<!-- CHART MURID PER KELAS -->
<script>
	const ctx = document.getElementById('muridPerKelas');
	let dataMurid = <?=json_encode($student_class)?>;

	let labels 		= [];
	let jumlahMurid = [];

	$.each(dataMurid, function (i, val) { 
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

<!-- CHART GURU -->
<script>
	function guruChart(){
		// Create root and chart
		var root = am5.Root.new("guruChart");
		var chart = root.container.children.push( 
		am5percent.PieChart.new(root, {
				layout: root.verticalHorizontal
			}) 
		);
	
		var Teacherdata = <?=json_encode($teacher_status)?>;
	
		// Create series
		var series = chart.series.push(
			am5percent.PieSeries.new(root, {
				name: "Series",
				valueField: "sales",
				categoryField: "country"
			})
		);

		// SETTING WARNA
		series.get("colors").set("colors", [
			am5.color(0x556ee6),
			am5.color(0xf46a6a),
			am5.color(0x5aaa95),
			am5.color(0x86a873),
			am5.color(0xbb9f06)
		]);

		series.data.setAll(Teacherdata);
	
		// Add legend
		var legend = chart.children.push(am5.Legend.new(root, {
			centerX: am5.percent(50),
			x: am5.percent(70),
			layout: root.horizontalLayout
		}));
	
		legend.data.setAll(series.dataItems);

	}
</script>

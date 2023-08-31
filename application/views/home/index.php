<!-- Chart Resources -->
<script src="//cdn.amcharts.com/lib/5/index.js"></script>
<script src="//cdn.amcharts.com/lib/5/xy.js"></script>
<script src="//cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

<style>
	#muridPerKelas { width: 100%; height: 250px; }
	#guruChart { width: 100%; height: 250px; }
</style>

<?php $user_level = $this->session->userdata('user_level'); ?>
<section class="explore-section section-padding" id="section_2">

	<!-- SECTION CHART UNTUK KEPSEK -->
	<?php if($user_level == 6): ?>
		<div class="container mt-5">
			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-2 text-center">
					<div class="container border rounded shadow-sm p-3">
						<span class="mb-2">Murid perkelas</span>
						<div id="muridPerKelas"></div>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 p-2 text-center">
					<div class="container border rounded shadow-sm p-3">
						<span class="mb-2">Guru</span>
						<div id="guruChart"></div>
					</div>
				</div>
				<div class="col-6">tes</div>
			</div>
		</div>
	<?php endif ?>

	<div class="container mt-5">

			<div class="col-12 text-center">
				<h2 class="mb-4">Pengumuman</h1>
			</div>

		</div>
	</div>

	

	<div class="container-fluid">
		<div class="row">
			<ul class="nav nav-tabs" id="myTab" role="tablist">

				<?php if($user_level == 4 || $user_level == 5) : ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas-tab-pane" type="button" role="tab" aria-controls="tugas-tab-pane" aria-selected="true">
							<i class="bi bi-pen-fill me-2"></i>Tugas
						</button>
					</li>
				<?php endif ?>

				<li class="nav-item" role="presentation">
					<button class="nav-link <?=($user_level == 3) ? 'active' : '' ?>" id="papan-pengumuman-tab" data-bs-toggle="tab" data-bs-target="#papan-pengumuman-tab-pane" type="button" role="tab" aria-controls="papan-pengumuman-tab-pane" aria-selected="true">
						<i class="bi bi-clipboard-check-fill me-2"></i>Papan Pengumuman
					</button>
				</li>
		

			</ul>
		</div>
	</div>

	<div class="container">
		<div class="row">

			<div class="col-12">
				<div class="tab-content" id="myTabContent">

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

					<!-- TAB PENGUMUMAN -->
					<div class="tab-pane fade <?=($user_level == 3 || $user_level == 6 || $user_level == 10) ? 'show active' : '' ?>" id="papan-pengumuman-tab-pane" role="tabpanel" aria-labelledby="papan-pengumuman-tab" tabindex="0">   
							
						<div class="row">
							<?php foreach($news as $key => $val) : ?>
								<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3">
									<div class="custom-block custom-block-overlay">
										<div class="d-flex flex-column h-100">
											<a href="<?=base_url('news/detail/').$val['id']?>">
												<div class="custom-block-overlay-text d-flex">
													<div>
														<h5 class="text-white mb-2"><?=$val['judul']?></h5>
														<span><?=date('d M Y H:i', strtotime($val['tanggal']))?></span>
														<p class="text-white"><?=substr(strip_tags($val['isi']), 0, 100) . ' ...'?></p>
														
														
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

				</div>

			</div>
		</div>
	</div>
</section>

<!-- CHART MURID PER KELAS -->
<script>
	$(document).ready(function () {
		let userLevel = <?=$user_level?>;
		if(userLevel == 6){
			muridPerKelas();
			guruChart();
		}
	});
</script>

<script>
	function muridPerKelas(){
		// Create root and chart
		var root = am5.Root.new("muridPerKelas");  
		root.setThemes([ am5themes_Animated.new(root) ]); 
		var chart = root.container.children.push( 
			am5xy.XYChart.new(root, { panY: false, wheelY: "zoomX", layout: root.verticalLayout }) 
		);
	
		var data = <?=json_encode($student_class)?>;
	
		// Craete Y-axis
		var yAxis = chart.yAxes.push(
			am5xy.ValueAxis.new(root, {
				extraTooltipPrecision: 1,
				renderer: am5xy.AxisRendererY.new(root, {})
			})
		);
	
		// CREATE XAXIS
		var xAxis = chart.xAxes.push(
			am5xy.CategoryAxis.new(root, {
				categoryField: "category",
				renderer: am5xy.AxisRendererX.new(root, {})
			})
		);
	
		xAxis.data.setAll(data);
	
		var series = chart.series.push(
			am5xy.ColumnSeries.new(root, {
				name: "Series",
				xAxis: xAxis,
				yAxis: yAxis,
				valueYField: "value",
				categoryXField: "category"
			})
		);

		// label angka yang ada di tengah bar
		series.bullets.push(function () {
			return am5.Bullet.new(root, {
			locationY: 0.5,
			sprite: am5.Label.new(root, {
					text: "{valueY}",
					fill: root.interfaceColors.get("alternativeText"),
					centerY: am5.p50,
					centerX: am5.p50,
					populateText: true
				})
			});
		});
	
		series.data.setAll(data);

	}
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
	
		var data = <?=json_encode($teacher_status)?>;
	
		// Create series
		var series = chart.series.push(
			am5percent.PieSeries.new(root, {
				name: "Series",
				valueField: "sales",
				categoryField: "country"
			})
		);

		series.data.setAll(data);
	
		// Add legend
		var legend = chart.children.push(am5.Legend.new(root, {
			centerX: am5.percent(50),
			x: am5.percent(70),
			layout: root.horizontalLayout
		}));
	
		legend.data.setAll(series.dataItems);

	}
</script>

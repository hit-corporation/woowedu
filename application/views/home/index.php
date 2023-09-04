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
	<?php if($user_level == 6): ?>
		<div class="container mt-5">
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
			am5.color(0x0062FF),
			am5.color(0xFF0000),
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

<!-- CHART TOTAL LOGIN PER BULAN GURU VS MURID -->
<script>
	var root = am5.Root.new("loginBulananChart");


	// Set themes
	// https://www.amcharts.com/docs/v5/concepts/themes/
	root.setThemes([
		am5themes_Animated.new(root)
	]);


	// Create chart
	// https://www.amcharts.com/docs/v5/charts/xy-chart/
	var chart = root.container.children.push(am5xy.XYChart.new(root, {
		panX: false,
		panY: false,
		wheelX: "panX",
		wheelY: "zoomX",
		layout: root.verticalLayout
	}));


	// Add legend
	// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
	var legend = chart.children.push(
		am5.Legend.new(root, {
			centerX: am5.p50,
			x: am5.p50
		})
	);

	var data = [{
			category: "Jan",
			categoryLabel: "Jan",
			teacher: 100,
			student: 75
		}, {
			category: "Feb",
			categoryLabel: "Feb",
			teacher: 80,
			student: 50
		}, {
			category: "Mar",
			categoryLabel: "Mar",
			teacher: 65,
			student: 40
		}, {
			category: "Apr",
			categoryLabel: "Apr",
			teacher: 50,
			student: 95
		}];


	// Create axes
	// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
	var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
		categoryField: "category",
		renderer: am5xy.AxisRendererX.new(root, {
			cellStartLocation: 0.1,
			cellEndLocation: 0.9,
			minGridDistance: 30
		}),
		tooltip: am5.Tooltip.new(root, {})
	}));

	xAxis.get("renderer").labels.template.adapters.add("text", function(text, target) {
		if (target.dataItem && target.dataItem.dataContext) {
			return target.dataItem.dataContext.categoryLabel;
		}
		return text;
	});

	xAxis.data.setAll(data);

	var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
		renderer: am5xy.AxisRendererY.new(root, {})
	}));


	// Add series
	// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
	function makeSeries(name, fieldName) {
	var series = chart.series.push(am5xy.ColumnSeries.new(root, {
		name: name,
		xAxis: xAxis,
		yAxis: yAxis,
		valueYField: fieldName,
		categoryXField: "category"
	}));

	series.columns.template.setAll({
		tooltipText: "{name}, {categoryX}:{valueY}",
		width: am5.percent(90),
		tooltipY: 0
	});

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
	series.appear();
	legend.data.push(series);
	}

	makeSeries("Guru", "teacher");
	makeSeries("Murid", "student");

	function makeRange(start, end, label) {
	var rangeDataItem = xAxis.makeDataItem({
		category: start,
		endCategory: end
	});
	
	var range = xAxis.createAxisRange(rangeDataItem);
	
	rangeDataItem.get("label").setAll({
		fill: am5.color(0x000000),
		text: label,
		fontWeight: "bold",
		dy: 25
	});
	
	rangeDataItem.get("grid").setAll({
		strokeOpacity: 0.2,
		location: 1
	});
	}

	// makeRange("2021 - JAN", "2021 - APR", "2021");
	// makeRange("2022 - Q1", "2022 - Q4", "2022");


	// Make stuff animate on load
	// https://www.amcharts.com/docs/v5/concepts/animations/
	chart.appear(1000, 100);
</script>

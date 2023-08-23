

<section class="explore-section section-padding" id="section_2">
	<div class="container mt-5">

			<div class="col-12 text-center">
				<h2 class="mb-4">Pengumuman</h1>
			</div>

		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<ul class="nav nav-tabs" id="myTab" role="tablist">

				<?php if($this->session->userdata('user_level') == 4 || $this->session->userdata('user_level') == 5) : ?>
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="design-tab" data-bs-toggle="tab" data-bs-target="#design-tab-pane" type="button" role="tab" aria-controls="design-tab-pane" aria-selected="true">
							<i class="bi bi-pen-fill me-2"></i>Tugas
						</button>
					</li>
				<?php endif ?>

				<!-- <li class="nav-item" role="presentation">
					<button class="nav-link" id="marketing-tab" data-bs-toggle="tab" data-bs-target="#marketing-tab-pane" type="button" role="tab" aria-controls="marketing-tab-pane" aria-selected="false">
						<i class="bi bi-clock-fill me-2"></i>Sesi
					</button>
				</li> -->

				<li class="nav-item" role="presentation">
					<button class="nav-link" id="papan-pengumuman-tab" data-bs-toggle="tab" data-bs-target="#papan-pengumuman-tab-pane" type="button" role="tab" aria-controls="papan-pengumuman-tab-pane" aria-selected="false">
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
					<div class="tab-pane fade show active" id="design-tab-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
						<div class="row d-flex justify-content-center">

							<?php foreach ($tasks as $key => $value) : ?>

								<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12  mb-4">
									<div class="custom-block bg-white shadow-lg">
										<!-- <a href="topics-detail.html"> -->
											<div class="d-flex">
												<div>
													<h5 class="mb-2"><?=$value['subject_name']?></h5>
													<p class="fs-12">Guru: <?=$value['teacher_name']?></p>
													<p><?=$value['note']?></p>
													<p class="fs-14 mt-4 bg-warning d-inline rounded p-1 mt-3 d-inline-block"><?= date('d M Y H:i', strtotime($value['due_date'])) ?></p>
												</div>
											</div>
										<!-- </a> -->
									</div>
								</div>

							<?php endforeach ?>

						</div>
					</div>

					<div class="tab-pane fade" id="marketing-tab-pane" role="tabpanel" aria-labelledby="marketing-tab" tabindex="0">
						<div class="row">
							<div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
									<div class="custom-block bg-white shadow-lg">
										<a href="topics-detail.html">
											<div class="d-flex">
												<div>
													<h5 class="mb-2"><?=date('l')?></h5>
													<span class="mb-0 fs-16"><?=date('d M Y')?></span> <span>10:30 - 12:00</span>
													<p class="mt-2">Matematika</p>
													<p>Guru: Saifudin</p>
													<p>pemahaman tentang deret bilangan tak hingga</p>
												</div>
											</div>
										</a>
									</div>
								</div>

								<div class="col-lg-4 col-md-6 col-12 mb-4 mb-lg-3">
									<div class="custom-block bg-white shadow-lg">
										<a href="topics-detail.html">
											<div class="d-flex">
												<div>
													<h5 class="mb-2"><?=date('l')?></h5>
													<span class="mb-0 fs-16"><?=date('d M Y')?></span> <span>13:00 - 13:30</span>
													<p class="mt-2">IPA</p>
													<p>Guru: Andi Malarangeng</p>
													<p>Pernafasan pada manusia</p>
												</div>
											</div>
										</a>
									</div>
								</div>

								<div class="col-lg-4 col-md-6 col-12">
									<div class="custom-block bg-white shadow-lg">
										<a href="topics-detail.html">
											<div class="d-flex">
												<div>
													<h5 class="mb-2"><?=date('l')?></h5>
													<span class="mb-0"><?=date('d M Y')?></span> <span>14:00 - 15:00</span>
													<p class="mt-2">Bahasa Inggris</p>
													<p>Guru: Albertus</p>
													<p>Grammar perfect simple present tense</p>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>

					<div class="tab-pane fade" id="papan-pengumuman-tab-pane" role="tabpanel" aria-labelledby="papan-pengumuman-tab" tabindex="0">   <div class="row">
							
							<div class="row">
								<?php foreach($news as $key => $val) : ?>
									<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3">
										<div class="custom-block custom-block-overlay">
											<div class="d-flex flex-column h-100">
												
												<div class="custom-block-overlay-text d-flex">
													<div>
														<h5 class="text-white mb-2"><?=$val['judul']?></h5>
														<p class="text-white"><?=$val['isi']?></p>
														<?php if(!empty($val['link'])) : ?>
															<a href="<?=$val['link']?>" class="btn custom-btn mt-2 mt-lg-3">Detail</a>
														<?php endif ?>
													</div>
												</div>
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
        
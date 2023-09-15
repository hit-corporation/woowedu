<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<form class="row mb-3" name="frm-filter">
		<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
			<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
				
			</select>
		</div>

		
		<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
            <select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
                
            </select>
		</div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
            <div class="btn-group">
                <button class="btn btn-primary" type="submit" ><i class="bi bi-search text-white"></i></button>
                <button class="btn btn-danger" type="reset" ><i class="bi bi-x-circle text-white"></i></button>
            </div>
            
        </div>
       
    </form>

	<div class="row">
		<!-- <div class="col"> -->
			<!--<?php for($i=0; $i<10; $i++):?>
			<div class="col-lg-4 col-md-6">
				<div class="card rounded border mb-4">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-3 col-xs-3">
							<div class="container mt-2">
								<img src="<?=base_url()?>assets/images/faq_graphic.jpg" alt="" width="90" height="125">
							</div>
						</div>
						<div class="col-lg-7 col-md-5 col-sm-9 col-xs-9">
							<p class="title">Buku Interaktif: PR Bahasa Indonesia X Semester 1 2022 </p>
							<p class="font-weight-bold fs-14 p-3">Kelas 10 Bahasa Indonesia (Interaktif)</p>
						</div>
					</div>
				</div>
			</div>
			<?php endfor?> -->
		<!-- </div> -->
		<div class="row mb-2">
			<div class="col-md-8 col-lg-10"></div>
			<div class="col-md-4 col-lg-2 d-flex flex-nowrap justify-content-end">
				<button type="button" class="btn btn-sm btn-success text-white shadow-sm rounded-pill" id="btn-add" data-bs-toggle="modal" data-bs-target="#modal-add">
					<i class="bi bi-plus font-size-12"></i> Tambah
				</button>
			</div>
		</div>

		<div class="row"></div>
		<?php 
			if($datamodel != 'grid'):
				$this->load->view('mapel/table_view');
			endif; 
		?>
	</div>


</div>

	
</section>

<!-- Modal add -->
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Materi</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column"  >
            <div class="row">
                <div class="col-12 col-lg-7">
                     
                    <div class="row align-items-top mb-3">
                        <div class="col-3">
                            <label class="m-0">Tema <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
							<input type="text" class="form-control form-control-sm" name="a_materi_tema_title" />
                        </div>			


                        <div class="col-3">
                            <label class="m-0">Sub Tema <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
							<input type="text" class="form-control form-control-sm" name="a_materi_sub_tema_title" />
                        </div>											
                        <div class="col-3">
                            <label class="m-0">Judul <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="text" class="form-control form-control-sm" name="a_materi_title" />
                        </div>
                        <div class="col-3">
                            <label class="m-0">No Urut <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="number" class="form-control form-control-sm" name="a_materi_no_urut" />
                        </div>												
                        <div class="col-3">
                            <label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select class="form-select form-select-sm col-11" name="a_materi_subject" data-live-search="true"></select>
                            <input type="hidden" name="a_materi_subject_text">
                        </div> 
												
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col-12">
                            <label>Deskripsi </label>
                            <textarea class="form-control form-control-sm w-100 h-100" rows="5" name="a_materi_note"></textarea>
                        </div>
                        <div class="col-12 pt-4">
                            <div class="d-flex flex-column">
                                <label class="mb-0">File</label>
                                <div class="input-group input-group-sm">
                                    <input type="file" class="form-control form-control-sm" id="videoFile" name="a_materi_video">
                                    <label class="form-label overflow-hidden" id="video-label" for="videoFile" data-browse="Unggah Video"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="a_id" />
            <input type="hidden" name="xsrf" />
        </form>
        <span class="w-100 d-flex flex-nogrow">
          <!-- PRogress bar-->
            <div id="upload-progress" class="progress w-100 d-none">
              <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          <!-- end PRogress bar-->
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-subject">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->

<!-- Modal Show -->
<div class="modal fade" id="modal-show" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <h5 class="modal-title text-white" id="tema"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="sub-tema" class="fs-5"></h6>
                <strong><span id="judul" class="fs-16"></span></strong>
                <p id="note" class="fs-14"></p>
                <div class="w-100 d-flex flex-nowrap justify-content-end align-items-center mt-3">
                    <a class="btn btn-success text-white" id="file-link">
                        <i class="bi bi-download"></i> Unduh File
                    </a>
                </div>
                
            </div>
            
        </div>
    </div>
</div>
<!-- End Modal Show -->

<script>
	// $('#basic-usage').select2({
	// 	theme: "bootstrap-5",
	// 	width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
	// 	placeholder: $( this ).data( 'placeholder' ),
	// });
</script>

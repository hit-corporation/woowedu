<style>
a.close_corner_top_left {
	position: absolute;
	top: -14px;
	left: -10px;
}
.mr-20 {
	margin-right:20px;
}
.dataTables_filter, .dataTables_length {
	display: none;
}

.bg-purple {
    background-color: var(--purple);
    color: var(--white);
}

.bg-pink {
    background-color: var(--pink);
    color: var(--white);
}

.bg-orange {
    background-color: var(--orange);
    color: var(--white);
}

#mc {
  height: 240px;
  overflow-y: auto;
}

[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
	position: static!important;
	left: 0px!important;
	opacity: 1!important;
	visibility: visible!important;
	pointer-events: all!important;
}		 
</style>
<div class="row">
	<div class="col-12">
		<div class="card"> 
		<div class="card-header"></div>
			<div class="card-body">
				<form class="form-horizontal searchCompany" action="#" method="post" id="search-transaction">
					<div class="row align-items-center">

						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
              <label class="mb-0">Kelas</label>
								<select class="form-control form-control-sm" name="s_kelas" id="s_kelas" placeholder="Nama Kelas" data-live-search="true" data-size="5"></select>
							</div>
						</div> 

						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Jenis</label>
								<select class="form-control form-control-sm" name="s_jenis" id="s_jenis" placeholder="Jenis" data-live-search="true" data-size="5"></select>
							</div>
						</div> 
						
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
              <label class="mb-0">Mata Pelajaran</label>
								<select class="form-control form-control-sm" name="s_mapel" id="s_mapel" placeholder="Mata Pelajarn" data-live-search="true" data-size="5"></select>
							</div>
						</div> 


						
						<div class="col-12 col-md-2 mt-4">
							<div class="btn-group btn-group-sm">
								<button type="button" class="btn btn-primary" id="search-button"><i class="fa fa-search"></i></button>
								<button type="button" class="btn btn-danger" id="reset-search"><i class="fa fa-times"></i></button>
							</div>
						</div>

					</div>
				</form>
				<hr>
				<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
				
					<a role="button" class="btn btn-warning text-white" id="btn-refresh"><i class="fas fa-sync"></i> <?=$this->lang->line('woow_button_refresh');?></a> 
					<a role="button" class="btn btn-sm btn-success waves-light waves-effect" id="btn-add" href="<?=html_escape('soal/forms')?>" target="_blank">
							<i class="fa fa-plus-circle fa-fw"></i> <?=$this->lang->line('woow_button_new');?>
          </a>							 	
	        		<button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
					<div class="btn-group btn-group-sm" role="toolbar">
						<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
							<i class="fa fa-upload fa-fw"></i> Import
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
							<a class="dropdown-item" href="<?=html_escape('assets/files/download/soal/soal.xlsx')?>">Download template</a>
						</div>
					</div>
					 
				</div>
				<div class="table-responsive mt-1">
					<table id="tbl_soal" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" id="select_all">
								</th>
								<th>ID Soal</th>
                <th>Kode Soal</th>
								<th>ID Kelas</th>   
								<th>Kelas</th>   
								<th>ID Mapel</th>   
								<th>Mata Pelajaran</th>   
                <th>Jenis Soal</th> 
								<th>Soal</th>   
                <th>Jawaban</th>
                <!--IF Multiple Choice
								<th>Pilihan A</th>
								<th>Pilihan B</th>
								<th>Pilihan C</th>
								<th>Pilihan D</th>
                <!-- if have file
								<th>FIle Soal</th>
                <th>File A</th>
								<th>File B</th>
								<th>File C</th>
								<th>File D</th>-->
                <th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>




<!-- Modal add -->
<section class="modal modal-full fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Soal</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <h4 class="mb-4 text-underline">KETERANGAN</h4>
                    <div class="row align-items-top mb-3">
                        <div class="col-4">
                            <label class="m-0">Kode Soal <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="text" class="form-control form-control-sm" name="a_soal_code" required/>
                        </div>
                        
                        <div class="col-4">
                            <label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select type="text" class="form-control form-control-sm col-10" name="a_soal_subject" data-live-search="true" required></select>
                            <button type="button" id="reset-subject" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
                            <input type="hidden" name="a_soal_subject_text">
                        </div>
                        <div class="col-4">
                            <label class="m-0">Kelas <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select type="text" class="form-control form-control-sm col-10" name="a_soal_class" data-live-search="true" required></select>
                            <button type="button" id="reset-class" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            <input type="hidden" name="a_soal_class_text">
                        </div>
                        <div class="col-4">
                            <label class="m-0">Jenis Soal <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select class="form-control form-control-sm col-10 selectpicker" name="a_soal_type" data-live-search="true" required>
                              <option value="1">Pilihan Ganda</option>
                              <option value="2">Essay</option>
                              <option value="3">Isian</option>
                            </select>
                            <button type="button" id="reset-type" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            <input type="hidden" name="a_soal_type_text">
                        </div>
                        <!-- <div class="col-4">
                            <label class="m-0">Bobot Nilai <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="number" class="form-control form-control-sm" name="a_soal_bobot" step=".1" value="1.0" min="1.0" required/>
                            <small>Nilai akan di kakulasi berdasarkan jumlah soal dan bobot yang di berikan</small>
                        </div> -->
                        <div class="col-4">
                            <label class="m-0">Jawaban <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <textarea class="form-control form-control-sm" name="a_soal_answer" rows="8" required></textarea>
                            <small>Untuk jawaban pilihan ganda gunakan kunci (contoh: a, b)</small>
                        </div>
                        <div class="col-4">
                            <label class="m-0">File pendukung</label>
                        </div>
                        <div class="col-8 mb-3">
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="a_soal_file">
                                <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                              </div>
                            </div>
                            <small>Untuk jawaban pilihan ganda gunakan kunci (contoh: a, b)</small>
                        </div>
                    </div>
                    
                </div>
                <div class="col">
                    <h4 class="mb-4 text-underline">SOAL</h4>
                    <div class="row">
                        <div class="col-12">
                            <input type="file" name="a_soal_file" class="d-none"/>
                            <div class="d-flex flex-column">
                                <label for="">Deskripsi Soal <span class="text-danger"><strong>*</strong></span</label>
                                <textarea name="a_soal_detail" class="form-control w-100" id="detail-soal" rows="160" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mc">
                      <div class="col-12">
                        <h4>Jawaban Pilihan Ganda</h4>
                        <table id="table-choices" class="table table-sm w-100">
                          <thead>
                            <tr>
                              <th>Pilihan</th>
                              <th>Teks</th>
                              <th>File</th>
                            </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="a_id" />
            <input type="hidden" name="xsrf" />
        </form>
        <span class="w-100 d-flex flex-nogrow">
            <!-- PRogress bar-->
          <div class="progress mt-2" id="import-progress-1">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <!-- end PRogress bar-->
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-soal">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->

<!-- Modal Export -->
<section class="modal fade" tabindex="-1" id="modal-export">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-capitalize text-light text-shadow">Export Data</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="export-area" class="container">
            <div class="row align-items-center">
                <span class="col-4">File Format</span>
                <span class="col-8">
                    <select name="file-format" class="form-control">
                        <option value="excel">EXCEL</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                        <option value="txt">TXT</option>
                        <option value="json">JSON</option>
                    </select>
                </span>
            </div>
            <div class="row mt-3">
                <span class="col-4">Data To Export</span>
                <div class="col-8 d-flex flex-column">
                   <div class="form-check">
                        <input type="radio" class="form-check-input" name="check-qty" value="all" checked/>
                        <label class="form-check-label">All (Max. 30000)</label>
                   </div>
                   <div class="form-check">
                        <input type="radio" class="form-check-input" name="check-qty" value="selected" />
                        <label class="form-check-label">Selected (Max 30000)</label>
                   </div>
                   <div class="d-flex flex-wrap align-item-center mt-2">
                        <span class="col-5"><label>Start Position</label></span>
                        <span class="col-7"><input type="number" name="start-position" class="form-control form-control-sm" min="1" value="1" style="width: 4rem" disabled/></span>
                   </div>
                   <div class="d-flex flex-wrap align-item-center mt-2">
                        <span class="col-5"><label>Total Records</label></span>
                        <span class="col-7"><input type="number" name="total-records" class="form-control form-control-sm" min="1" max="30000" value="1" style="width: 6rem" disabled/></span>
                   </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="ml-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Export">
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- End Modal Export -->
<!-- Modal Import -->
<section class="modal fade" tabindex="-1" id="modal-import">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-capitalize text-light text-shadow">import Data</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="import-soal" class="container">
 
            <div class=" form-group row align-items-center">
                <span class="col-4">
                    <label>File Upload</label>
                </span>
                <span class="col-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control-sm" name="file-upload" 
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <label class="custom-file-label xl_label">Pilih XL File</label>
                    </div>
                </span>
            </div>
						
            <div class=" form-group row align-items-center">
                <span class="col-4">
                    <label>Pictures Upload</label>
                </span>
                <span class="col-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control-sm" name="zip-upload" 
                            accept=".zip">
                        <label class="custom-file-label zip_label">Pilih File Zip</label>
                    </div>
                </span>
            </div>						
                
            <div class="row mt-5">
                <div class="ml-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Import">
                </div>
            </div>
        </form>
        <!-- PRogress bar-->
        <div class="progress" id="import-progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <!-- end PRogress bar-->
      </div>
    </div>
  </div>
</section>
<!-- End Modal Import -->
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
								<input type="search" class="form-control form-control-sm" name="s_title" id="s_title" placeholder="Judul">
							</div>
						</div>  
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
								<input type="search" class="form-control form-control-sm" name="s_mapel" id="s_mapel" placeholder="Nama Mapel">
							</div>
						</div> 
						<div class="col-12 col-md-2 align-self-center">
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
					<button type="button" class="btn btn-sm btn-success waves-light waves-effect" id="btn-add" data-toggle="modal" data-target="#modal-add">
							<i class="fa fa-plus-circle fa-fw"></i> <?=$this->lang->line('woow_button_new');?>
					</button>							 	
	        		<button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
					<div class="btn-group btn-group-sm" role="toolbar">
						<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
							<i class="fa fa-upload fa-fw"></i> Import
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
							<a class="dropdown-item" href="<?=html_escape('assets/files/download/materi/template/materi.xlsx')?>">Download template</a>
						</div>
					</div>
					 
				</div>
				<div class="table-responsive mt-1">
					<table id="tbl_materi" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" id="select_all">
								</th>
								<th>ID Materi</th>   
								<th>Judul</th>   
								<th>Tanggal Buka</th>    
								<th>ID Mapel</th>   
								<th>Nama Mapel</th>
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
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Materi</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column"  >
            <div class="row">
                <div class="col-12 col-lg-7">
                    <h4 class="mb-4 text-underline">KETERANGAN</h4>
                    <div class="row align-items-top mb-3">
                        <div class="col-3">
                            <label class="m-0">Judul <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="text" class="form-control form-control-sm" name="a_materi_title" />
                        </div>
                        <div class="col-3">
                            <label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <select type="text" class="form-control form-control-sm col-11" name="a_materi_subject" data-live-search="true"></select>
                            <button type="button" id="reset-subject" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
                            <input type="hidden" name="a_materi_subject_text">
                        </div> 

                        <div class="col-3">
                            <label class="m-0">Parent <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
													<div id="materi_parent"></div> 
                        </div>			


                        <div class="col-3">
                            <label class="m-0">Materi Require <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
													<div id="materi_require"></div> 
                        </div>		
												
												
                        <div class="col-3">
                            <label class="m-0">Tanggal Di Buka <span class="text-danger"><strong>*</strong></span></label>
                        </div>
                        <div class="col-8 mb-3">
                            <input type="text" class="form-control form-control-sm" name="a_materi_date" />
                        </div>
												
                        <div class="col-3">
                            <label>Deskripsi </label>
                        </div>
                        <div class="col-8 mb-3">
                            <textarea class="form-control form-control-sm w-100 h-100" rows="12" name="a_materi_note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <h4 class="mb-4 text-underline">UPLOAD VIDEO</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <video id="preview" class="w-100" height="265" controls></video>
                                <h6 class="mt-1">Preview</h6>
                                <div class="custom-file mt-2">
                                    <input type="file" class="custom-file-input" id="videoFile" name="a_materi_video">
                                    <label class="custom-file-label overflow-hidden" id="video-label" for="videoFile" data-browse="Unggah Video">Pilih Video</label>
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
<section class="modal modal-full fade" tabindex="-1" id="modal-video">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-capitalize text-light text-shadow">Materi</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12 d-flex flex-column">
              <h3 id="judul" class="display-4 text-capitalize"></h3>
              <span class="clearfix">
              <video id="video-file" class="float-left" width="480" height="360" controls style="object-fit: fill;"></video>
              <p id="note" class="text-justify pl-4" style="max-height: 380px; overflow: auto">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed cursus turpis urna, vel imperdiet velit tempus eget. Curabitur nibh erat, sollicitudin quis tempus non, imperdiet laoreet dui. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris accumsan fringilla urna, sed porta tellus convallis sit amet. Quisque nec velit et nulla feugiat rutrum. Quisque ornare ipsum nec justo cursus, nec lobortis sem elementum. Proin vel felis quam. Sed at augue eu lectus ullamcorper luctus. Aenean sed faucibus leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec a nisi velit. Donec sed urna nibh. Nulla ut mi arcu.
Duis suscipit accumsan maximus. Aliquam sed odio luctus, rutrum quam ac, luctus risus. Suspendisse ac molestie risus, non scelerisque mauris. Vivamus sit amet ligula a nulla elementum consectetur a ut magna. Morbi vel rutrum sem. Cras tristique molestie augue condimentum blandit. Etiam a dolor venenatis, iaculis libero et, aliquet magna.
Duis in nisi id nibh commodo convallis. Sed vitae vestibulum turpis. Proin in ipsum vitae mauris varius commodo. Proin elementum nibh vitae augue mollis cursus. Integer volutpat sodales facilisis. Sed iaculis feugiat fringilla. In blandit semper risus in malesuada.
Quisque accumsan tincidunt justo nec molestie. Nam id bibendum lectus, sit amet semper risus. Praesent ultrices at orci quis scelerisque. Etiam erat neque, hendrerit ac bibendum ac, aliquam vel ante. Praesent urna tortor, fringilla eu lectus sed, vulputate mollis felis. In aliquam sapien arcu, nec aliquet enim semper eu. Nam sit amet sodales orci, at dignissim tortor. Proin fermentum tortor urna, non placerat sem luctus sed. Integer velit orci, interdum ac lorem ac, hendrerit lacinia mauris. Vivamus placerat sed nunc sed lacinia. Praesent iaculis consequat bibendum. Praesent egestas felis id nunc pretium hendrerit. Nunc nec pharetra erat. Sed velit neque, hendrerit quis consectetur quis, varius et mi. Ut tristique ipsum enim, in sollicitudin tellus sagittis at.
              </p>
              </span>
             
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-subject">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- Modal Watch Video -->

<!-- END Modal Watch Video -->

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
        <form name="import-area" class="container">
            <div class="form-group row">
                <span class="col-4">
                    <label>File Format</label>
                </span>
                <span class="col-8">
                    <div class="form-check align-items-center">
                        <input type="radio" class="form-check-input" name="import-format" value="excel" checked/>
                        <label class="form-check-label">Excel</label>
                    </div>
                </span>
            </div>
            <div class=" form-group row align-items-center">
                <span class="col-4">
                    <label>File Upload</label>
                </span>
                <span class="col-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control-sm" name="file-upload" 
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <label class="custom-file-label">Pilih File</label>
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
        <div class="progress mt-2" id="import-progress-1">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <!-- end PRogress bar-->
      </div>
    </div>
  </div>
</section>
<!-- End Modal Import -->
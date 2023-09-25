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


					 
				<form class="form-horizontal searchStudent" action="#" method="post" id="search-student">
           <div class="row"> 
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="s_student_name">Nama Wali</span>
                        </div>
                        <input type="search" class="form-control" aria-describedby="s_student_name" name="s_student_name" placeholder="Cari Nama Wali Murid" />
                    </div>
                </div>
                <div class="col">
                    <button type="button" id="btn-search_student" class="btn btn-primary shadow">
                        <i class="fa fa-search fa-fw"></i>
                        Cari
                    </button>
                </div>
           </div>
				</form>
				<hr>
 
				
				<div class="btn-group" role="toolbar">
				
								
						<button type="button" class="btn btn-warning waves-light waves-effect" id="btn-refresh"><i class="fa fa-sync" aria-hidden="true"></i> Refresh</button>
						<button type="button" class="btn btn-success waves-light waves-effect" id="btn-add" data-toggle="modal" data-target="#modal-add">
								<i class="fa fa-plus-circle fa-fw"></i> Tambah
						</button>
						<button type="button" class="btn btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> Hapus</button> 
						<div class="btn-group" role="toolbar">
								<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
										<i class="fa fa-upload fa-fw"></i> Import
								</button>
								<div class="dropdown-menu">
										<a class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
										<a class="dropdown-item" href="<?=html_escape('assets/files/download/template_guru.xlsx')?>">Download import template</a>
								</div>
						</div>
				</div>

										
				<div class="table-responsive mt-1">
					<table id="tbl-parent" class="table table-striped table-sm nowrap">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" class="custom-control-input" id="select_all">
								</th>
								<th>#</th> 
								<th>#</th> 
								<th>Nama</th>   
								<th>Wali Dari</th>   
								<th>Alamat</th>   
								<th>Phone</th>   
								<th>Email</th> 								
                        
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
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Data Wali Murid</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row align-items-center">
                <div class="col-4">
                    <label class="m-0">Username <span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_parent_name" /></div>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col-4">
                    <label class="m-0">Nama Panjang <span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_full_name" /></div>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col-4">
                    <label class="m-0">Kelas <span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8">
                    <select  class="form-control form-control-sm" name="a_class">
                        <option>==Pilih Kelas==</option>
                        <?php foreach($data_class as $rec){ ?>
                        <option value="<?php echo $rec['class_id']; ?>"><?php echo $rec['class_name']; ?></option>
                        <?php } ?>
                    </select> 
                </div>
            </div>						
            <div class="row align-items-center mt-2">
                <div class="col-4">
                    <label class="m-0">Alamat <span class="text-danger"> </span></label>
                </div>
                <div class="col-8"> 
                    <textarea cols="20" class="form-control form-control-sm" name="a_address" rows="4" ></textarea>
                </div>
            </div>						
												
            <div class="row align-items-center mt-2">
                <div class="col-4">
                    <label class="m-0">Phone <span class="text-danger"> </span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_phone" /></div>
            </div>						
            <div class="row align-items-center mt-2">
                <div class="col-4">
                    <label class="m-0">Email <span class="text-danger"> </span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_email" /></div>
            </div>						

            <input type="hidden" name="a_parent_id" />
            <input type="hidden" name="xsrf" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-student">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->


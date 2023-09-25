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

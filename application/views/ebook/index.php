<section class="explore-section section-padding" id="section_2">
	



<div class="container">

	<h4>Ebook</h4>

	<!-- section search -->
	<form class="row mt-4 align-items-end" name="frm-search">
		<div class="col-4">
			<label>Judul</label>
			<input type="text" class="form-control form-control-sm" name="filter[title]" placeholder="Ketik nama atau sebagian nama yang di cari"/>
		</div>	
		<div class="col-4">
			<label>Kategori</label>
			<select class="form-select form-select-sm" name="filter[category]"></select>
		</div>	
		<div class="col-4">
			<div class="btn-group btn-group-sm">
				<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search text-white"></i></button>
				<button type="reset" class="btn btn-sm btn-danger"><i class="bi bi-x text-white"></i></button>
			</div>
		</div>	
	</form>

	<!-- content -->
	<div class="row mt-4" id="content">

		<div id="grid" class="row">
			<div class="d-flex flex-column justify-content-center w-100">
				<i class="bi bi-database-x text-center" style="font-size: 5.8rem"></i>
				<h4 class="text-center">NO DATA</h4>
			</div>
		</div>

		<nav aria-label="Page navigation example" class="d-flex justify-content-center">
			<ul class="pagination">
				
			</ul>
		</nav>
		
	</div>
		
	
	
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="assets/js/ebook.js" defer></script>

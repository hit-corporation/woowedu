<section class="explore-section section-padding" id="section_2">
	
<pre class="d-none">
	<?php print_r($book) ?>
</pre>

<div class="container">

	<h4></h4>

	<!-- section search -->
	<div class="row mt-4">

        <div class="col-12 col-lg-3">
            <img class="img-thumbnail" src="<?= html_escape(base_url('assets/images/ebooks/cover/'.$book['cover_img'])) ?>"/>
        </div>
        <div class="col-12 col-lg-9">
            <div class="card" style="min-height: 60vh">
                <div class="card-body h-100">
                    <h3><?=$book['title']?></h3>
                    <p class="fs-14"><?=$book['description']?></p>
                    <table class="mt-3 table table-sm table-borderless fs-14">
                        <tr>
                            <td style="width: 100px"><h6 class="mb-0 fs-14">Pengarang</h6></td>
                            <td>:</td>
                            <td><?=$book['author']?></td>
                        </tr>
                        <tr>
                            <td><h6 class="mb-0 fs-14">Penerbit</h6></td>
                            <td>:</td>
                            <td><?=$book['publisher_name']?></td>
                        </tr>
                        <tr>
                            <td><h6 class="mb-0 fs-14">ISBN</h6></td>
                            <td>:</td>
                            <td><?=$book['isbn']?></td>
                        </tr>
                    </table>
                    
                </div>
                <div class="card-footer d-flex flex-nowrap w-100 justify-content-end">
                        <a class="btn btn-sm btn-primary" href="<?=html_escape(base_url('ebook/open_book?id='.$book['id']))?>">Baca</a>
                    </div>
            </div>
            
        </div>

	</div>
	
	
</section>

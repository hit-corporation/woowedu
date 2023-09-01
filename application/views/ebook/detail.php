<section class="explore-section section-padding" id="section_2">
	
<pre>
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
            <div class="card">
                <div class="card-body">
                    <h3><?=$book['title']?></h3>
                    <p class="fs-14"><?=$book['description']?></p>
                </div>
            </div>
            
        </div>

	</div>
	
	
</section>

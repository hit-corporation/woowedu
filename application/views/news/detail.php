<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<h4><?=$data['judul']?></h4>
	<br>

	<p><?=date('d M Y H:i', strtotime($data['tanggal']))?></p>

	<br><br>

	<?=$data['isi']?>
	

</div>

</section>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Topic Listing Bootstrap 5 Template</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
        <link href="<?=base_url('assets/css/bootstrap.css')?>" rel="stylesheet">

        <link href="<?=base_url('assets/css/bootstrap-icons.css')?>" rel="stylesheet">
        <link href="<?=base_url('assets/css/custom.css')?>" rel="stylesheet">

        <link href="<?=base_url('assets/css/templatemo-topic-listing.css')?>" rel="stylesheet">
		
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<script>var BASE_URL = "<?=base_url()?>";</script>
<!--

TemplateMo 590 topic listing

https://templatemo.com/tm-590-topic-listing

-->
    </head>
    
    <body id="top">

        <main>

            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="index.html">
                        <i class="bi-back"></i>
                        <span>Topic</span>
                    </a>

                    <div class="d-lg-none ms-auto me-4">
                        <a href="#top" class="navbar-icon bi-person smoothscroll person-sm"></a>
						<div class="profile-container p-3 sm">
							<p><a href="<?=base_url()?>user"><i class="bi-person fa-user"></i> Profile</a></p>
							<p><i class="bi-gear-fill"></i> Setting</p>
							<a href="<?=base_url()?>auth/logout"><p class="text-red"><i class="bi-box-arrow-left"></i> Logout</p></a>
						</div>
                    </div>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-lg-5 me-lg-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="sesi">Sesi</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link" href="mapel">Mapel</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="section_4">Ebook</a>
                            </li>
    
                            <li class="nav-item">
                                <a class="nav-link" href="section_5">Tugas</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="news">Pengumuman</a>
                            </li>

                           
                        </ul>

                        <div class="d-none d-lg-block">
                            <a href="#top" class="navbar-icon bi-person smoothscroll person-lg"></a>
							<div class="profile-container p-3 lg">
								<p><a href="<?=base_url()?>user"><i class="bi-person fa-user"></i> Profile</a></p>
								<p><i class="bi-gear-fill"></i> Setting</p>
								<a href="<?=base_url()?>auth/logout"><p class="text-red"><i class="bi-box-arrow-left"></i> Logout</p></a>
							</div>
                        </div>
                    </div>
                </div>
            </nav>

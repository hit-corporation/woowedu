<!doctype html>
<html lang="en">
    <head>
        <base href="<?=base_url()?>" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>Topic Listing Bootstrap 5 Template</title>

        <!-- CSS FILES -->        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
                        
        <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">

        <link href="<?=base_url('assets/css/bootstrap-icons.css')?>" rel="stylesheet">
        <link href="<?=base_url('assets/css/custom.css')?>" rel="stylesheet">

        <link href="<?=base_url('assets/css/templatemo-topic-listing.css')?>" rel="stylesheet">
		
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<script>var BASE_URL = document.querySelector('base').href;</script>

        <?php if(!empty($add_css)): 
                foreach($add_css as $css):
        ?>
        <link rel="stylesheet" href="<?=html_escape(base_url($css))?>" />
        <?php
                endforeach; 
            endif; 
        ?>

    <style>
        html {
            min-height: 100%;
        }

        main {
            min-height: 80vh;
        }
    </style>

    </head>
    
    <body id="top">

		<?php 
			$userid 	= $this->session->userdata('userid');
			$user 		= $this->db->where('userid', $userid)->get('users')->row_array();
			$imageLink 	= base_url('assets/images/users/').$user['photo'];
			$user_level	= $user['user_level'];

			$student = ($user_level == 4) ? $this->db->where('nis', $user['username'])->get('student')->row_array() : '';
			$student_id = $student['student_id'];

            $name = '';
            
            switch($user_level)
            {
                case 3:
                    $name = $this->db->where('nik', $user['username'])->get('teacher')->row_array()['teacher_name'];
                    break;
                case 4:
                    $name = $this->db->where('nis', $user['username'])->get('student')->row_array()['student_name'] ?? '';
                    break;
                case 6:
                    $name = $this->db->where('nik', $user['username'])->get('teacher')->row_array()['teacher_name'] ?? '';
                    break;
                default:
                    $name = $this->db->where('username', $user['username'])->get('parent')->row_array()['name'] ?? '';
                    break;
            }
		?>

        <main>

            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand pt-0" href="#">
                        <img src="<?= base_url('assets/images/logo-small-in2.png') ?>" alt="" width="120">
                    </a>

                    <div class="d-lg-none ms-auto me-4">
                        <a href="#top" class="navbar-icon bi-person smoothscroll person-sm"></a>
						<div class="profile-container p-3 sm">
							<div class="profile-image-menu">
								<img src="<?=$imageLink?>" alt="" width="100">
								<span class="d-block mb-3">Hi <?=$name?></span>
							</div>
							<hr>
							<p><a href="<?=base_url()?>user"><i class="bi-person fa-user"></i> Profile</a></p>
							<p><i class="bi-gear-fill"></i> Setting</p>
							<a class="d-block" href="<?=base_url()?>auth/logout"><p class="text-red"><i class="bi-box-arrow-left"></i> Logout</p></a>
						</div>
                    </div>
    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-lg-5 me-lg-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?=base_url()?>">Home</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=base_url()?>sesi">Sesi</a>
                            </li>

							<?php if($user_level == 6) : ?>
								<li class="nav-item">
									<a class="nav-link" href="<?=base_url()?>student">Siswa</a>
								</li>

								<li class="nav-item">
									<a class="nav-link" href="<?=base_url()?>teacher">Guru</a>
								</li>
							<?php endif ?>


    
                            <li class="nav-item">
                                <a class="nav-link" href="<?=base_url()?>mapel">Mapel</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?=base_url()?>ebook">Ebook</a>
                            </li>
    
							<!-- JIKA USER LEVEL GURU OR MURID -->
							<?php if($user_level == 3 || $user_level == 4) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=($user_level == 3) ? base_url('teacher/tasks') : base_url('student/detail/').$student_id ?>">Tugas</a>
                            </li>
							<?php endif ?>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="<?=base_url()?>news">Pengumuman</a>
                            </li>

                           
                        </ul>

                        <div class="d-none d-lg-block">
                            <a href="#top" class="navbar-icon bi-person smoothscroll person-lg"></a>
							<div class="profile-container p-3 lg">
								<div class="profile-image-menu">
									<img src="<?=$imageLink?>" alt="" width="100">
									<span class="d-block mb-3">Hi <?=$name?></span>
								</div>
								<hr>
								<p><a href="<?=base_url()?>user"><i class="bi-person fa-user"></i> Profile</a></p>
								<p><i class="bi-gear-fill"></i> Setting</p>
								<a class="d-block" href="<?=base_url()?>auth/logout"><p class="text-red"><i class="bi-box-arrow-left"></i> Logout</p></a>
							</div>
                        </div>
                    </div>
                </div>
            </nav>

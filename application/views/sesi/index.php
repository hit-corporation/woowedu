<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<h4>Jadwal Sesi</h4>

	 
	<div class="row">
		<div class="col-8"></div>
		<div class="col-4"> 
			<a href="<?=base_url()?>sesi/create" class="btn btn-success" style>
				+ Buat Sesi
			</a>
		</div>
	</div>

	<!-- content -->
	<div class="row" style="padding-top:20px">  
		<script src="<?=base_url('assets/fullcalendar/index.global.js')?>"></script> 
			<div class="col-8">
		    <div id="calendar" class="col"></div>
			</div>
			<div class="col-4" id="sesi_content">
				
 			
				
			</div>
	</div>
		
 
	
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
<script>
$(document).ready(function() {
	var calendarEl = document.getElementById('calendar');
 

    var calendar = new FullCalendar.Calendar(calendarEl, {

      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'listDay,listWeek'
      },

      // customize the button names,
      // otherwise they'd all just say "list"
      views: {
        listDay: { buttonText: 'list day' },
        listWeek: { buttonText: 'list week' }
      },

      initialView: 'listWeek',
      initialDate: '<?=date('Y-m-d')?>',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      			events: {
				url: '<?php echo base_url(); ?>sesi/loaddata',
				error: function() {
					$('#script-warning').show();
				}
			},		 
			eventClick: function(info) {
				var eventObj = info.event;
				$('#sesi_content').load('<?php echo base_url(); ?>sesi/sesidetail/'+eventObj.id);
					
				/*
				var ev = calEvent.className;
				var _eurl = '<?php echo base_url(); ?>calendar/detail?id='+calEvent.id+'';			
				$('#calendar_detail').load(_eurl);
				$("#calendar_detail").show();*/

			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
    });

    calendar.render();
		
		
		
});


	function deleteSesi(id){
		Swal.fire({
			title: 'Anda yakin untuk menghapus?',
			text: "Hapus data sesi!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					type: "POST",
					url: BASE_URL+"sesi/delete",
					data: {
						id: id
					},
					dataType: "JSON",
					success: function (response) {
						if(response.success == true){
							Swal.fire('Deleted!', response.message, 'success');
							window.location.href = BASE_URL+'sesi';
						}
					}
				});

				
			}
		})
	}
</script>
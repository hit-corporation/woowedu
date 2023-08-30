<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<h4>Jadwal Sesi</h4>

	 
	<div class="row mt-4">
		<div class="container d-flex justify-content-end p-0">
			<a href="<?=base_url()?>news/create" class="btn btn-success">
				+ Buat Sesi
			</a>
		</div>
	</div>

	<!-- content -->
	<div class="row mt-4" id="news-content">
	  
		<link href="<?=base_url('assets/calendar-master/global.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/calendar-master/event-calendar.min.css')?>" rel="stylesheet">
		<script src="<?=base_url('assets/calendar-master/event-calendar.min.js')?>"></script> 
		    <div id="ec" class="col"></div>
		
	</div>
		
 
	
</section>
 
<script type="text/javascript">
    let ec = new EventCalendar(document.getElementById('ec'), {
        view: 'timeGridWeek',
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'dayGridMonth,timeGridWeek'
        },
        buttonText: function (texts) {
            texts.resourceTimeGridWeek = 'resources';
            return texts;
        },
        resources: [
            {id: 1, title: 'Resource A'},
            {id: 2, title: 'Resource B'}
        ],
        scrollTime: '09:00:00',
        events:  createEvents(),
        views: {
            timeGridWeek: {pointer: true},
            resourceTimeGridWeek: {pointer: true}
        },
        dayMaxEvents: true,
        nowIndicator: true,
        selectable: true
    });

    function createEvents() {
        var _jsdata = <?=$jsdata?>;
        return _jsdata;
    }

    function _pad(num) {
        let norm = Math.floor(Math.abs(num));
        return (norm < 10 ? '0' : '') + norm;
    }
</script>
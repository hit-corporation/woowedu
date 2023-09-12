
  (function ($) {
  
  "use strict";

    // MENU
    $('.navbar-collapse a').on('click',function(){
      $(".navbar-collapse").collapse('hide');
    });
    
    // CUSTOM LINK
    $('.smoothscroll').click(function(){
      var el = $(this).attr('href');
      var elWrapped = $(el);
      var header_height = $('.navbar').height();
  
      scrollToDiv(elWrapped,header_height);
      return false;
  
      function scrollToDiv(element,navheight){
        var offset = element.offset();
        var offsetTop = offset.top;
        var totalScroll = offsetTop-navheight;
  
        // $('body,html').animate({
        // scrollTop: totalScroll
        // }, 300);
      }
    });

    // $(window).on('scroll', function(){
    //   function isScrollIntoView(elem, index) {
    //     var docViewTop = $(window).scrollTop();
    //     var docViewBottom = docViewTop + $(window).height();
    //     var elemTop = $(elem).offset().top;
    //     var elemBottom = elemTop + $(window).height()*.5;
    //     if(elemBottom <= docViewBottom && elemTop >= docViewTop) {
    //       $(elem).addClass('active');
    //     }
    //     if(!(elemBottom <= docViewBottom)) {
    //       $(elem).removeClass('active');
    //     }
    //     var MainTimelineContainer = $('#vertical-scrollable-timeline')[0];
    //     var MainTimelineContainerBottom = MainTimelineContainer.getBoundingClientRect().bottom - $(window).height()*.5;
    //     $(MainTimelineContainer).find('.inner').css('height',MainTimelineContainerBottom+'px');
    //   }
    //   var timeline = $('#vertical-scrollable-timeline li');
    //   Array.from(timeline).forEach(isScrollIntoView);
    // });

		window.addEventListener('click', function(e){
			let profile = $('.profile-container');
			profile.addClass('d-none');
			profile.removeClass('d-block');
		});

		$('.person-lg').on('click', function(){
			let profile = $('.profile-container.lg');
			if(profile.hasClass('d-block')){
				profile.addClass('d-none');
				profile.removeClass('d-block');
			}else{
				profile.addClass('d-block');
				profile.removeClass('d-none')
			}
		});

		$('.person-sm').on('click', function(){
			let profile = $('.profile-container.sm');
			if(profile.hasClass('d-block')){
				profile.addClass('d-none');
				profile.removeClass('d-block');
			}else{
				profile.addClass('d-block');
				profile.removeClass('d-none')
			}
		});

		// KETIKA NOTIF ICON DI KLIK
		$('.notif-group').on('click', function(){
			let notif = $('.notif-content-container');
			if(notif.hasClass('d-block')){
				notif.addClass('d-none');
				notif.removeClass('d-block');
			}else{
				notif.addClass('d-block');
				notif.removeClass('d-none')
			}

			// JALANKAN AJAX UNTUK AMBIL DATA NOTIF
			$.ajax({
				type: "GET",
				url: BASE_URL+"home/notif_data",
				dataType: "JSON",
				success: function (response) {
					if(response.success == true){
						$('.notif-content').html('');
						$.each(response.data, function (i, val) {
							const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember"];
							let date = new Date(val.created_at);
							$('.notif-content').append(`<div class="notif-list" onclick="notifList(${val.notif_id}, '${val.link}')">
										<div class="row">
											<div class="col-2">
												<i class="${(val.type == 'TASK') ? 'bi bi-list-task' : 'bi bi-newspaper'}"></i>
											</div>
											<div class="col-10">
												<span>${val.title.substring(0, 100).replace(/(<([^>]+)>)/ig, '')}</span>
												<br>
												<span class="fs-14 notif-date">${date.getDate()+` `+monthNames[date.getMonth()]+` `+date.getFullYear()+` `+date.getHours()+`:`+date.getMinutes()}</span>
												<span class="${(val.seen == false) ? `dotted-notif` :  ``}"></span>
											</div>
										</div>
									</div>`);
						});
					}
				}
			});

		});

		// CHANGE BADGE TOTAL TOTIF
		$.ajax({
			type: "GET",
			url: BASE_URL+"home/notif",
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					$('.notif-number').html(response.total);
				}
			}
		});

		
  
  })(window.jQuery);

	// notif-list DI KLIK
	function notifList(id, link){
		$.ajax({
			type: "GET",
			url: BASE_URL+"home/notif_update",
			data: {
				notif_id: id
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					window.location.href = BASE_URL+link;
				}
			}
		});
	}

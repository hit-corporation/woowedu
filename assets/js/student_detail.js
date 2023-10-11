var currentPage = 1;
var student_id 	= $('#student_id').val();
var class_id 	= $('#class_id').val();
let startDate 	= moment().startOf('month').startOf('day').format('YYYY-MM-DD');
let endDate		= moment().startOf('day').format('YYYY-MM-DD');

getSummary(student_id, startDate, endDate);

$(document).ready(function () {
	$('input[name="daterange"]').daterangepicker({
		opens: 'right',
		minYear: 2000,
		maxYear: 2025,
		showDropdowns: true,
		ranges: {
				'Today': [moment().startOf('day'), moment().endOf('day')],
				'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
				'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
				'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
			}
		}, function(start, end, label) {
			console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

			getSummary(student_id, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
		}
	);

	// ###################################################################################################
	// ########################################## SESI CALENDAR ##########################################
	// ###################################################################################################

	var calendarEl = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		height:500,
		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'listDay,listWeek'
		},

		// customize the button names,
		// otherwise they'd all just say "list"
		views: {
			listDay: { buttonText: 'list day' },
			listWeek: { buttonText: 'list week' },
		},

		initialView: 'listWeek',
		initialDate: moment().format('YYYY-MM-DD'),
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		events: {
				url: BASE_URL + 'student/sesi_load_data?student_id='+student_id,
				error: function() {
					$('#script-warning').show();
				}
			},
		eventDidMount: function(info) {
				let title = info.el.children[2].innerText;
				let teacher = info.event._def.extendedProps.teacher;
				let subject_name = info.event._def.extendedProps.subject_name;
				info.el.children[2].innerHTML = (`<p class="fs-14">${title}</p>
					<p class="text-success fw-bold fs-14">Guru: ${teacher}</p>
					<span>Mata Pelajaran: <span style="background-color: #3989d9; color: white; padding-left: 5px; padding-right: 5px; border-radius: 10px; box-shadow: 3px 3px 5px lightblue;">${subject_name}</span></span>`);
			},
		eventClick: function(info) {
				var eventObj = info.event;
				$('#sesi_content').load(BASE_URL + 'sesi/sesidetail/' + eventObj.id);
			},
		loading: function(bool) {
				$('#loading').toggle(bool);
			}
	});

	calendar.render();
});

// ######################################### FUNGSI UNTUK UBAH DATA CONTENT SUMMARY #########################################

function getSummary(student_id, start, end){
	$.ajax({
		type: "POST",
		url: BASE_URL+"student/get_summary",
		data: {
			student_id, student_id,
			start: start,
			end: end
		},
		dataType: "JSON",
		success: function (response) {
			$('.total-task')[0].children[0].innerHTML = response.total_task;
			$('.total-task-submit')[0].children[0].innerHTML = response.total_task_submit;
		}
	});
}

// ###########################################################################################
// ################################## DATA TABLE LIST TUGAS ##################################
// ###########################################################################################

var tableTask = $('#tableTask').DataTable({
		serverSide: true,
		ajax: {
			url: BASE_URL + 'student/get_task',
			method: 'GET',
			data: {
				student_id: student_id
			}
		},
		select: {
			style:	'multi',  
			selector: 'td:first-child'
		},
		columns: [
			{
				data: 'task_id',
				visible: false
			},
			{
				data: 'code',
			},
			{
				data: 'subject_name',
			},
			{
				data: 'title',
			},
			{
				data: 'teacher_name',
			},
			{
				data: 'available_date',
				render(data, row, type, meta) {
					return moment(data).format('DD MMM YYYY, HH:mm');
				}
			},
			{
				data: 'due_date',
				render(data, row, type, meta){
					return moment(data).format('DD MMM YYYY, HH:mm');
				}
			},
			{
				data: 'task_submit',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<span class="text-white bg-danger p-1 rounded">Belum Dikerjakan</span>`;
				}
			},
			{
				data: 'task_file_answer',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? `<a href="${BASE_URL+'assets/files/student_task/'+class_id+'/'+data}"><i class="bi bi-file-text-fill fs-20"></i></a>` : `-`;
				}
			},
			{
				data: 'task_note',
				class: 'text-center',
				render(data, row, type, meta){
					return (data) ? data.substring(0, 100) : `-`;
				}
			},
			{
				data: 'score',
				class: 'text-center',
				render(data, row, type, meta){
					return `<b>${(data) ? data : '-'}</b>`;
				}
			},
			{
				data: null,
				class: 'text-center',
				render(data, type, row, meta) {
					let btnEdit = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-success edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></a>`;
					let btnView = `<a href="${BASE_URL+'task/detail/'+row.task_id}" class="btn btn-primary view_subject rounded-5"><i class="bi bi-eye text-white"></i></a>`;
					
					let endDt = new Date(row.due_date);	
					let now = new Date();
					
					let container = `<div class="btn-group btn-group-sm float-right">
									${(endDt < now) ? '' : btnEdit}
									${(row.task_submit) ? btnView : ''}
								</div>`;
					return container;
				}
			}
		]
	});


// ##########################################################################################
// ################################## DATA TABLE LIST EXAM ##################################
// ##########################################################################################


var tableExam = $('#tableExam').DataTable({
	serverSide: true,
	ajax: {
		url: BASE_URL + 'student/get_exam',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child',
	},
	columns: [
		{
			data: 'exam_id',
			visible: false
		},
		{
			data: 'subject_name',
		},
		{
			data: 'category_name',
		},
		{
			data: 'teacher_name',
		},
		{
			data: 'exam_total_nilai',
			class: 'text-center',
			render(data, row, type, meta){
				return (data) ? `<b>${data}</b>` : `-`;
			}
		},
		{
			data: 'end_date',
			class: 'text-center',
			render(data, row, type, meta){
				return moment(data).format('DD MMM YYYY, HH:mm');
			}
		},
		{
			data: 'exam_submit',
			class: 'text-center',
			render(data, row, type, meta){
				return (data) ? moment(data).format('DD MMM YYYY, HH:mm') : `<span class="text-white bg-danger p-1 rounded">Belum Dikerjakan</span>`;
			}
		},
		{
			data: null,
			class: 'text-center',
			render(data, type, row, meta) {
				var view = `<div class="btn-group btn-group-sm float-right">
								<button class="btn btn-success edit_subject rounded-5"><i class="bi bi-pencil-square text-white"></i></button>
							</div>`;
				return view;
			}
		}
	]
});

// #############################################################################################
// ################################## DATA TABLE HISTORY BOOK ##################################
// #############################################################################################

var tableBookHistory = $('#tableBookHistory').DataTable({
	serverSide: true,
	ajax:{
		url: BASE_URL + 'student/get_history_book',
		method: 'GET',
		data: {
			student_id: student_id
		}
	},
	select: {
		style: 'multi',
		selector: 'td:first-child'
	},
	columns: [
		{
			data: 'book_id',
			visible: false
		},
		{
			data: 'cover_img',
			class: 'text-center',
			render(data, type, row, meta){
				return `<img src="${data}" alt="" width="100">`;
			}
		},
		{
			data: 'max',
			render(data, type, row, meta){
				let tanggal = moment(data).format('DD MMM YYYY HH:mm');
				return tanggal;
			}
		},
		{
			data: 'book_code'
		},
		{
			data: 'title'
		},
		{
			data: 'category_name'
		},
		{
			data: 'author'
		},
		{
			data: 'publish_year'
		},
		{
			data: 'description',
			render(data, type, row, meta){
				return data.substring(0,100)+' ...';
			}
		},
		{
			data: null,
			class: 'text-center',
			render(data, type, row, meta) {
				var view = `<div class="btn-group btn-group-sm float-right">
								<button class="btn btn-success edit_subject rounded-5" onclick="showModalBook(${row.book_id})"><i class="bi bi-eye text-white"></i></button>
							</div>`;
				return view;
			}
		}
	]
});

// ############################################################################################
// ################################## SHOW MODAL DETAIL BUKU ##################################
// ############################################################################################

function showModalBook(book_id){
	$.ajax({
		type: "GET",
		url: BASE_URL+"student/book_detail",
		data: {
			book_id: book_id
		},
		dataType: "JSON",
		success: function (response) {
			if(response.success == true){
				let data = response.data;
				$('#modal-show').modal('show');
				$('[data-item="cover_img"]')[0].src = data.cover_img;
				$('[data-item="book_code"]')[0].innerHTML = data.book_code;
				$('[data-item="author"]')[0].innerHTML = data.author;
				$('[data-item="publisher_name"]')[0].innerHTML = data.publisher_name;
				$('[data-item="publish_year"]')[0].innerHTML = data.publish_year;
				$('[data-item="isbn"]')[0].innerHTML = data.isbn;
				$('[data-item="description"]')[0].innerHTML = data.description;
				$('.read-link')[0].href = BASE_URL+'ebook/open_book?id='+book_id;
			}

		}
	});
}

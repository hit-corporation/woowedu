(($, base_url) => {
    'use strict';

		const form = document.forms['form-add'];
			
    let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
				
    var formAdd = document.getElementById('form-add'),
				formEdit = document.getElementById('form-edit'),
        is_update = 0;
				
    var table = $('#tbl_student').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/student/get_all',
            type: 'GET',
            data: function(d) {   
                return d;
            }
        }, select: {
			style:	'multi', 
			selector: 'td:first-child'
		},
        columns: [  {
            data:null,
            width: '30px',
            className: 'select-checkbox ',
            checkboxes: {
                selectRow: true
            },
            orderable: false,
            render(data, row, type, meta) {
                return '';
            }
        },{
            data: 'student_id', 
                visible: false 
        },{
            data: 'class_id', 
                visible: false 
        }  , {
            data: 'nis'
        }  , {
            data: 'student_name'
        }  , {
            data: 'class_name'
        }   , {
            data: 'address'
        }  , {
            data: 'phone'
        }   , {
            data: 'email'
        }    , {
            data: 'parent_name'
        }  , {
            data: 'parent_phone'
        }   , {
            data: 'parent_email'
        }, {
            data: null,
            render(data, row, type, meta) {
                var view = '<div class="btn-group btn-group-sm float-right">'+ 
                                '<button class="btn btn-success edit_student"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_student"><i class="bx bx-trash font-size-12"></i></button>' +
                            '</div>';
                return view;
            }
        } ],
				pageLength: 8,
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}

    });
 
 
    //select_all
    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });

    $('#btn-add').on('click', e => {
        is_update = false;
        form.reset(); 
        form['xsrf'].value = csrfToken.content;
    });

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    });


	 


	/**
	 * SAVE DATA
	 */
	const btnSubmit = document.getElementById('save-student'); 

	$('#tbl_student tbody').on('click', '.btn.edit_student', e => {
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data(); 
		form['a_student_id'].value = row[0].student_id;
		form['a_student_name'].value = row[0].student_name; 
		form['a_class'].value = row[0].class_id; 
		form['a_nis'].value = row[0].nis; 
		form['a_address'].value = row[0].address; 
		form['a_phone'].value = row[0].phone; 
		form['a_email'].value = row[0].email; 
		form['a_parent_name'].value = row[0].parent_name; 
		form['a_parent_phone'].value = row[0].parent_phone; 
		form['a_parent_email'].value = row[0].parent_email; 	
		form['xsrf'].value = csrfToken.content; 
		$('#modal-add').modal('show');
	});

 // submit
	btnSubmit.addEventListener('click', e => {
		e.preventDefault();

		let frmObj = { 
				student_id: form['a_student_id'].value,
				student_name: form['a_student_name'].value, 
				nis: form['a_nis'].value, 
				class_id: form['a_class'].value, 
				address: form['a_address'].value, 
				phone: form['a_phone'].value, 
				email: form['a_email'].value, 
				parent_name: form['a_parent_name'].value, 
				parent_phone: form['a_parent_phone'].value, 
				parent_email: form['a_parent_email'].value,  
				xsrf_token: form['xsrf'].value
		};
		let conf = {};
		if(is_update) {
				conf = {
						url: base_url + 'api/student/edit_data',
						type: 'PUT',
						contentType: 'application/json',
						data: JSON.stringify(frmObj)
				};
		} else {
				conf = {
						url: base_url + 'api/student/add_data',
						type: 'POST',
						contentType: 'application/x-www-form-urlencoded',
						data: $.param(frmObj)
				};
		}

		$.ajax({
			url: conf.url,
			type: conf.type,
			data: conf.data,
			contentType: conf.contentType,
			beforeSend(xhr, obj) {
					Swal.fire({
							html: 	'<div class="d-flex flex-column align-items-center">'
							+ '<span class="spinner-border text-primary"></span>'
							+ '<h3 class="mt-2">Loading...</h3>'
							+ '<div>',
							showConfirmButton: false,
							width: '10rem'
					});
			},
			success(resp) {
				Swal.fire({
						type: resp.err_status,
						title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
						html: resp.message
			});
		csrfToken.content = resp.token;
				},
				error(err) {
						let response = JSON.parse(err.responseText);
		Swal.fire({
			type: response.err_status,
			title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
			html: response.message
		});
		if(response.hasOwnProperty('token'))
			csrfToken.setAttribute('content', response.token);
				},
				complete() {
						table.ajax.reload();
				}
		});
		$('#modal-add').modal('hide');
});
		

	/**
	 * DELETE SINGLE
	 */
  $('#tbl_student tbody').on('click', '.btn.delete_student', e =>{
			e.preventDefault();
			let row = table.row($(e.target).parents('tr')).data(); 
			Swal.fire({
					title: "Anda Yakin?",
					text: "Data yang dihapus tidak dapat dikembalikan",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn btn-success mt-2",
					cancelButtonColor: "#f46a6a",
					confirmButtonText: "Ya, Hapus Data",
					cancelButtonText: "Tidak, Batalkan Hapus" 
      }).then(reslt => {
				if(!reslt.value)
						return false;
				$.ajax({
					url: base_url + 'api/student/delete_data',
					type: 'DELETE',
					contentType: 'application/json',
					data: JSON.stringify({bulk: false, data: row.student_id, xsrf_token: csrfToken.content}),
					beforeSend(xhr, obj) {
						Swal.fire({
							html: 	'<div class="d-flex flex-column align-items-center">'
							+ '<span class="spinner-border text-primary"></span>'
							+ '<h3 class="mt-2">Loading...</h3>'
							+ '<div>',
							showConfirmButton: false,
							width: '10rem'
						});
					},
					success(resp) {
						Swal.fire({
							type: resp.err_status,
							title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
							html: resp.message
						});
						csrfToken.content = resp.token;
					},
					error(err) {
						let response = JSON.parse(err.responseText);
						Swal.fire({
							type: response.err_status,
							title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
							html: response.message
						});
						if(response.hasOwnProperty('token'))
							csrfToken.setAttribute('content', response.token);
					},
					complete() {
							table.ajax.reload();
					}
				});
      });
   });
	 
	 /**
	 *DELETE MULTI
	 */
	document.querySelector('#delete_all').addEventListener('click', e => {
		e.preventDefault();
		let rows = table.rows({selected: true}).data(),
			count = table.rows({selected: true}).count();
		if(count === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
				text: "Harap pilih data yang akan dihapus terlebih dahulu.",
				confirmButtonClass: "btn btn-warning mt-2",
				type: "warning"
			});
      return false;
    }

		let datas = [];
		for(let i=0;i<rows.length;i++) { 
				datas.push(rows[i].student_id);
		}
		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus" 
		}).then(reslt => {
			if(!reslt.value)
					return false;
			$.ajax({
				url: base_url + 'api/student/delete_data',
				type: 'DELETE',
				contentType: 'application/json',
				data: JSON.stringify({bulk: true, data: datas, xsrf_token: csrfToken.content}),
				beforeSend(xhr, obj) {
					Swal.fire({
						html: 	'<div class="d-flex flex-column align-items-center">'
						+ '<span class="spinner-border text-primary"></span>'
						+ '<h3 class="mt-2">Loading...</h3>'
						+ '<div>',
						showConfirmButton: false,
						width: '10rem'
					});
				},
				success(resp) {
					Swal.fire({
						type: resp.err_status,
						title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
						html: resp.message
					});
					csrfToken.content = resp.token;
				},
				error(err) {
					let response = JSON.parse(err.responseText);
					Swal.fire({
						type: response.err_status,
						title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
						html: response.message
					});
					if(response.hasOwnProperty('token'))
							csrfToken.setAttribute('content', response.token);
				},
				complete() {
						table.ajax.reload();
				}
			});
		})
  });	 
	
	/**
	 * IMPORT DATA
	 */

	let impForm = document.forms['import-student'];
	let impFormat = impForm['import-format'],
			impFile = impForm['file-upload'];

	let importFormat = null,
		importFile = null;
		impFile.addEventListener('change', e => {
			e.preventDefault();
			let lbl = document.querySelector('.custom-file-label');
			lbl.innerHTML = e.target.files[0].name;
			importFile = e.target.files[0];
	});
	impForm.addEventListener('submit', e => {
			e.preventDefault();
			let fdata = new FormData();
			fdata.append('format', impFormat.value);
			fdata.append('upload-file', importFile);
			// upload 
			$.ajax({
					url: base_url + 'api/student/import',
					type: 'POST',
					data: fdata,
					contentType: false,
					processData: false,
					xhr() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
									myXhr.upload.addEventListener('progress', (evt) => {
											if(evt.lengthComputable) {
													var percentage = (evt.loaded/evt.total) * 100;
													var progress = document.getElementById('import-progress').getElementsByClassName('progress-bar')[0];
													console.log(progress);
													progress.style.width = percentage + '%';
													progress.setAttribute('aria-valuenow', percentage);
											}

									}, false);
							}
							return myXhr;
					},
					success(reslv) {
						Swal.fire({
							type: 'success',
							title:`<h5 class="text-success text-uppercase">Berhasil</h5>`,
							html: 'Data berhasil diimport'
						});							
						let progress = Math.ceil(reslv.prog / reslv.total * 100);
						console.log(progress);
					},
					error(err) {
							let response = JSON.parse(err.responseText);
							Swal.fire({
								type: response.err_status,
								title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
								html: response.message
							});
					},
					complete() {				
							table.ajax.reload();
							$('#modal-import').modal('hide');
					}
			}); 
			
	});
	
})(jQuery, document.querySelector('base').href);
'use strict';
const form = document.forms['form-add'];
const embedFile = document.querySelector('#embed-file');
let isUpdate = 0;


/**
 * Data table init
 * @date 9/11/2023 - 3:58:36 PM
 *
 * @type {*}
 */
const table = $('#tbl-materi').DataTable({
    ajax: BASE_URL + 'materi/list',
    serverSide: true,
    processing: true,
    columns: [
        {
            data: 'materi_id',
            visible: false
        },
        {
            data: 'subject_id',
            visible: false
        },
        {
            data: 'subject_name'
        },
        {
            data: 'tema_title'
        },
        {
            data: 'sub_tema_title',
            width: '15%'
        },
        {
            data: 'title'
        },
        {
            data: 'no_urut',
            width: '40px'
        },
        {
            data: null,
            render(data, row, type, meta) {
                var view = '<div class="btn-group btn-group-sm float-right">'+
                                '<button class="btn bg-purple text-white view_materi"><i class="bi bi-eye font-size-12"></i></button>' +
                                '<button class="btn bg-orange text-white upload_soal"><i class="bi bi-upload font-size-12"></i></button>' +
                                '<button class="btn btn-success edit_materi"><i class="bi bi-pen text-white font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_materi"><i class="bi bi-trash-fill text-white font-size-12"></i></button>' +
                            '</div>';
                return view;
            },
            width: '60px'
        }
    ],
});


/**
 * Get Subjects From api
 * @date 9/11/2023 - 3:40:18 PM
 *
 * @async
 * @returns {Response}
 */
const getSubject = async () => {
    try
    {
        const url = new URL(`${ADMIN_URL}/api/subject/getAll`);
        const f = await fetch(url.href);

        return await f.json();
    }
    catch(err)
    {
        console.log(err);
    }
}

const resetForm = () => {
    $('select[name="a_materi_subject"]').val(null).trigger('change');
    form.reset();
}

/**
 * Button Add Click Handler
 * @date 9/13/2023 - 10:02:26 AM
 *
 * @param {*} e
 */
const btnAddClick = e => {
    isUpdate = 0;
    resetForm();
}


/**
 * Button Update Click Handler
 * @date 9/13/2023 - 9:59:51 AM
 *
 * @param {HTMLButtonElement} 
 */
const btnUpdateClick = e => {
    isUpdate = 1;
    const count = table.row(e.target.parentNode.closest('tr')).count(),
          item = table.row(e.target.parentNode.closest('tr')).data();

    form['a_id'].value = item.materi_id;
    form['a_materi_tema_title'].value = item.tema_title; 
    form['a_materi_sub_tema_title'].value = item.sub_tema_title; 
    form['a_materi_title'].value = item.title; 
    form['a_materi_no_urut'].value = item.no_urut; 
    form['a_materi_subject_text'].value = item.subject_name;
    form['a_materi_note'].value = item.note;
    $('select[name="a_materi_subject"]').val(item.subject_id).trigger('change');
    //document.getElementById("preview").src = item.materi_file;

    $('#modal-add').modal('show');
}

/**
 * Input File Handler
 * @date 9/12/2023 - 4:33:04 PM
 *
 * @async
 * @returns {*}
 */
const inputFileHandler = e => {
    if(!e.files && !e.files[0])
        throw new Error("No File Uploaded");

    const reader = new FileReader();
    reader.onload = e => embedFile.src = e.target.result;
    reader.readAsDataURL(e.files[0]);
}

/**
 * submit form
 * @date 9/11/2023 - 3:51:34 PM
 *
 * @async
 * @param {e: HTMLFormElement} 
 * @returns {*}
 */
const submitMateri = async e => {
    e.preventDefault();
    
    try
    {
        let url = isUpdate == 1 ? new URL(`${ADMIN_URL}/api/materi/edit`) : new URL(`${ADMIN_URL}/api/materi/save`);
        const formData = new FormData(e.srcElement);

        const f = await fetch(url.href, {
            method: 'POST',
            body: formData
        });

        const resp = await f.json();

       if(!f.ok)
       {
            Swal.fire({
                type: resp.err_status,
                title: '<h5 class="text-danger text-uppercase">'+resp.err_status+'</h5>',
                html: `<h5 class="text-danger">${resp.message} </h5>`,
                timer: 1500
            }); 

            return false;
       } 

       Swal.fire({
            type: resp.err_status,
            title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
            html: `<h5 class="text-success">${resp.message} </h5>`,
            timer: 1500
        })
        .then(t => $('#modal-add').modal('hide'));
    }
    catch(err)
    {
        console.log(err.responseText);
    }
}


/**
 * View Data
 * @date 9/13/2023 - 1:53:24 PM
 *
 * @param {HTMLButtonElement} e
 */
const showData = e => {
    let judul = document.querySelector('#judul'),
        tema = document.querySelector('#tema'),
        subTema = document.querySelector('#sub-tema'),
        note = document.querySelector('#note'),
        fileLink = document.querySelector('#file-link'),
        item = table.row(e.target.parentNode.closest('tr')).data();

    tema.innerText = item.tema_title.replace(' : ', '. ');
    subTema.innerText = item.sub_tema_title.replace(' : ', '. ');
    judul.innerText = item.title.replace(' : ', ': ');
    note.innerHTML = item.note;
    fileLink.href = new URL(`${BASE_URL}/assets/files/materi/` + item.materi_file).href;
   
    $('#modal-show').modal('show');
}

/*
============================
        DELETE
============================
*/

function erase(data, isBulk) {
    return $.ajax({
        url: ADMIN_URL + '/api/materi/delete',
        type: 'DELETE',
        data: JSON.stringify({data: data, isBulk: isBulk}),
        contentType: 'application/json',
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
            //csrfToken.content = resp.token;
        },
        error(err) {
            let response = JSON.parse(err.responseText);
            Swal.fire({
                type: response.err_status,
                title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                html: response.message
            });
            //if(response.hasOwnProperty('token'))
            //	csrfToken.setAttribute('content', response.token);
        },
        complete() {
            table.ajax.reload();
        }
    });
}


 // DELETE ONE
$('#tbl-materi tbody').on('click', '.btn.delete_materi', e => {
    var row = table.row($(e.target).parents('tr')).data();
    Swal.fire({
        title: "Anda Yakin ?",
        text: "Data yang dihapus tidak dapat dikembalikan",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn btn-success mt-2",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Ya, Hapus Data",
        cancelButtonText: "Tidak, Batalkan Hapus",
        closeOnConfirm: false,
        closeOnCancel: false
    }).then(t => {
        if(t.value) {
            erase(row.materi_id, 0);
        }
    })
});

(async ($) => {

    const subjects = [...(await getSubject()).data].map(x => ({ id: x.id_mapel, text: x.nama_mapel }));

    $('#tbl-materi > tbody').on('click', '.btn.view_materi', e => {
        isUpdate = 1;
    });

    $('select[name="a_materi_subject"]').select2({
        theme: "bootstrap-5",
        data: subjects,
        placeholder: 'Pilih Mapel',
        allowClear: true
    });
    
    // Submit
    form.addEventListener('submit', async e => await submitMateri(e));
    document.querySelector('#save-subject').addEventListener('click', e => {
        const evt = new Event("submit");
        form.dispatchEvent(evt);
    });

    // Input File
    document.querySelector('#videoFile').addEventListener('change', inputFileHandler);
    // Button Add
    document.getElementById('btn-add').addEventListener('click', e => btnAddClick(e));
    // Update Click
    $('#tbl-materi > tbody').on('click', '.btn.edit_materi', e => btnUpdateClick(e));
    $('#tbl-materi > tbody').on('click', '.btn.view_materi', e => showData(e));
    //
})(jQuery);


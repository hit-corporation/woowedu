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
                                '<button class="btn btn-success edit_subject"><i class="bi bi-pen text-white font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_subject"><i class="bi bi-trash-fill text-white font-size-12"></i></button>' +
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


/**
 * Input File Handler
 * @date 9/12/2023 - 4:33:04 PM
 *
 * @async
 * @returns {*}
 */
const inputFileHandler = e => {
    if(!e.files)
        throw new Error("No File Uploaded");

    const reader = new FileReader();

    console.log(e);

    reader.onload = e => embedFile.src = e.target.result;
    //reader.readAsDataURL(e.files[0]);
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
        const formData = Object.fromEntries(new FormData(e.srcElement).entries());
        const body = new URLSearchParams(formData).toString();

        const f = await fetch(`${ADMIN_URL}/api/materi/save`, {
            method: 'POST',
            
            body: body
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


(async ($) => {

    $('#tbl-materi > tbody').on('click', '.btn.view_materi', e => {
        isUpdate = 1;
    });

    $('select[name="a_materi_subject"]').select2({
        theme: "bootstrap-5",
        data: [...(await getSubject()).data].map(x => ({ id: x.id_mapel, text: x.nama_mapel }))
    });
    
    // Submit
    form.addEventListener('submit', async e => await submitMateri(e));
    document.querySelector('#save-subject').addEventListener('click', e => {
        const evt = new Event("submit");
        form.dispatchEvent(evt);
    });


    // Input File
    document.querySelector('#videoFile').addEventListener('change', inputFileHandler);
})(jQuery);


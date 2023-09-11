'use strict';

    const table = $('#tbl-materi').DataTable({
        ajax: BASE_URL + 'materi/list',
        serverSide: true,
        processing: true,
        scrollX: true,
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
                data: 'sub_tema_title'
            },
            {
                data: 'title'
            },
            {
                data: 'no_urut'
            },
            {
                data: null,
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">'+
                                    '<button class="btn bg-orange text-white upload_soal"><i class="bi bi-upload font-size-12"></i></button>' +
                                    '<button class="btn btn-success edit_subject"><i class="bi bi-pen text-white font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_subject"><i class="bi bi-trash-fill text-white font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
        ],
    });
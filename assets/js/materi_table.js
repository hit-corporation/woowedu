'use strict';

    const table = $('#tbl-materi').DataTable({
        ajax: BASE_URL + 'materi/list',
        serverSide: true,
        processing: true,
        columns: [
            {
                data: 'materi_id'
            },
            {
                data: 'subject_id'
            },
            {
                data: 'subject_name'
            },
            {
                data: 'tema'
            },
            {
                data: 'sub-tema'
            },
            {
                data: 'judul'
            },
            {
                data: 'no_urut'
            },
            {
                data: null,
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">'+
                                    '<button class="btn bg-orange text-white upload_soal"><i class="fas fa-arrow-down font-size-12"></i></button>' +
                                    '<button class="btn bg-purple text-white view_video"><i class="fas fa-desktop font-size-12"></i></button>' +
                                    '<button class="btn btn-success edit_subject"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_subject"><i class="bx bx-trash font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
        ],
    });
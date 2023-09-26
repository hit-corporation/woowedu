'use strict';
const base_url = document.querySelector('base').href;


const table = $('#tbl-parent').DataTable({
    serverSide: true,
    processing: true,
    ajax: {
        url: new URL(base_url + 'orangtua/list')
    },
    columns: [
        {
            data: null,
            className: 'select-checkbox',
            checkboxes: {
                selectRow: true
            },
            orderable: false,
            render(data, type, row, _meta) {
                return '';
            }
        },
        {
            data: 'parent_id',
            visible: false
        },
        {
            data: 'username',
            visible: false
        },
        {
            data: 'name'
        },
        {
            data: 'wali_dari'
        },
        {
            data: 'address'
        },
        {
            data: 'phone'
        },
        {
            data: 'email'
        },
        {
            data: null,
            render(data, row, type, meta) {
                var view = '<div class="btn-group btn-group-sm float-right">'+ 
                                '<button class="btn btn-success edit_student"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_student"><i class="bx bx-trash font-size-12"></i></button>' +
                            '</div>';
                return view;
            }
        }
    ],
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

$('#btn-refresh').on('click', e => {
    table.ajax.reload();
});
'use strict';
const base_url = document.querySelector('base').href,
      form = document.forms['form-add'],
      selectStudent = document.querySelector('select[name="a_children"]');


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

// SELECT CHILDREN


const getStudents = async () => {

    try 
    {
        const f = await fetch(base_url + 'api/student/get_all');
        const j = await f.json();
        
        return j.data;
    } 
    catch (err) 
    {
        console.log(err);
    }
}


const setStudentSelect = async () => {
    try 
    {
        const students = [...await getStudents()];
        selectStudent.innerHTML = null;
    
        Array.from(students, item => {
            const option = document.createElement('option');

            option.text = `${item.student_name} - ${item.nis}`;
            option.value = item.student_id;

            selectStudent.add(option);
        });
    } 
    catch (err) 
    {
        console.log(err);
    }
}

(async $ => {

    await setStudentSelect();
    $('select[name="a_children"]').selectpicker({
        liveSearch: true,
        deselectAllText: true,
        noneSelectedText:  '- Pilih Murid -',
    });

})(jQuery);
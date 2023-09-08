<table id="tbl-materi" class="table table-sm table-striped">
    <thead class="bg-primary">
        <tr>
            <th></th>
        </tr>
    </thead>
</table>

<script defer>


    const table = $('#tbl-materi').DataTable({
        ajax: BASE_URL + 'materi/list',
        serverSide: true,
        processing: true,
        columns: [
            
        ],
    });
</script>
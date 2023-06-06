$(function() {
    initRolePermissionDatatable();
    //init
    $('.alert').hide();
});

//global variable
let table = '';

const initRolePermissionDatatable= () => {
    table = $('#dataTable').DataTable({
        // order: [[0, "desc"]],
        processing: true,
        serverSide: true,
        ajax: "/admin/roles",
        columns: [
            {  data: 'id', name:'id'},
            {  data: 'title', name:'title'},
            {  data: 'permissions'},
            {  data: 'action', name:'action', orderable: false, searchable: false},
        ],
        columnDefs: [{
            targets: 2,
            data: "permissions",
            render: function(data,type,row)
            {
                let permissions = '';
                $.each(data, function(i,e) {
                    // title += <e.title+ '&nbsp;'
                    permissions += `<p class=" btn btn-primary" > ${e.title} </p> &nbsp;`
                    // $(title).append($('<p />').addClass('bg-gradient-success').html(e.title))
                });
                // console.log($(permissions).text());
                return permissions;
            }

        }]
        ,
        oLanguage: {sProcessing:`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`}
    });
}

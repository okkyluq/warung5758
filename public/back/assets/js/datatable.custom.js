$.extend( $.fn.dataTable.defaults, {
    autoWidth: false,
    columnDefs: [{ 
        orderable: false,
        width: '100px',
        targets: [ 5 ]
    }],
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: '<span>Filter:</span> _INPUT_',
        emptyTable: "Data Belum Ada !",
        zeroRecords: "Data Tidak Ditemukan !",
        searchPlaceholder: 'Ketik Nama Barang',
        lengthMenu: '<span>Tampilkan :</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
    },
    drawCallback: function () {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
    },
    preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
    }
});

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});



$(document).ready(function() {
    $('.btndelete_orderlist').click(function() {
        var tdh = $(this);
        var id = $(this).attr("id");


        Swal.fire({
            title: 'Do you want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: '../resources/templates/back/ordertdelete.php',
                    type: 'post',
                    data: {
                        piddd: id
                    },
                    success: function(data) {
                        tdh.parents('tr').hide();
                    }


                });


                Swal.fire(
                    'Deleted!',
                    'Your Invoice has been deleted.',
                    'success'
                )
            }
        })


    });

});



$(document).ready(function() {
    $('#table_orderlist').DataTable({
        "autoWidth": false
        // "order": [
        //     [0, "desc"]
        // ]
    });
});
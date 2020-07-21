$(document).ready(function () {
    $('.select-order-type').on('change', function () {
        var url = $(this).data('url');
        var type = $(this).val();
        $.ajax({
            url : url,
            type: "get",
            data:{
                type :type
            },
            success: function(data){
                $('.table_orders').html(data);
            }
        });
    });
});

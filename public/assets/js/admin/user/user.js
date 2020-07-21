$(document).ready(function(){

    // $(document).on('change', '.selectpage', function () {
    //     var page = $('.selectpage').val();
    //     var url = $(this).data('url');
    //     $.ajax({
    //         url: url,
    //         type: "get",
    //         data:{
    //             page: page
    //         },
    //         success: function(data){
    //             if(data == 0){
    //                 alert('some thing went wrong');
    //             }else{
    //                 $('.table_users').html(data);
    //             }
    //         }
    //     })
    // });

    var timeout = null;
    $(document).on('keyup', '#search', function(){
        clearTimeout(timeout);
        var url = $('#search').data('url');
        var keyword = $(this).val();
        timeout = setTimeout(function() {
            $.ajax({
                url: url,
                type: 'get',
                data:{
                    keyword: keyword
                },
                success: function(data){
                    $('.table_users').html(data);
                }
            });
        }, 1000);
    });

    $(document).on('keyup', '#search_deleted', function(){
        clearTimeout(timeout);
        var url = $('#search_deleted').data('url');
        var keyword = $(this).val();
        timeout = setTimeout(function() {
            $.ajax({
                url: url,
                type: 'get',
                data:{
                    keyword: keyword
                },
                success: function(data){
                    $('.table_users').html(data);
                }
            });
        }, 1000);
    });

    $(document).ajaxComplete(function() {
        $('.pagination li a').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url     : url,
                success : function(data) {
                    $('.table_users').html(data);
                }
            });
        });
    });
});

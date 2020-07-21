$(document).ready(function () {
    var timeout = null;
    $(document).on('keyup', '#search', function () {
        clearTimeout(timeout);
        var url             = $(this).data('url');
        var keyword         = $(this).val();
        var item_per_page   = $('.selectpage').val();
        timeout = setTimeout(function () {
            $.ajax({
                url     : url,
                type    : "get",
                data    :{
                    keyword         : keyword,
                    item_per_page   : item_per_page
                },
                success : function(data){
                    $('.table_authors').html(data);
                }
            });
        }, 1000);
    })

    $(document).on('change', '.selectpage', function () {
        var item_per_page   = $(this).val();
        var url             = $(this).data('url');
        var keyword         = $('#search').val();
        $.ajax({
            url     : url,
            type    : "get",
            data    :{
                keyword         : keyword,
                item_per_page   : item_per_page
            },
            success : function(data){
                $('.table_authors').html(data);
            }
        });
    });

    $(document).ajaxComplete(function () {
        $('.pagination li a').click(function (e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url     : url,
                success : function(data){
                    $('.table_authors').html(data);
                }
            });
        });
    });
});

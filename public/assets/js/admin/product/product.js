$(document).ready(function() {
    $('.authors').select2({
        placeholder: 'Chọn/tìm kiếm',
        allowClear: true,
    });
    $('.categories').select2({
        placeholder: 'select categories',
        allowClear: true,
    });

    $(".select2-dropdown--below").css('min-width','200px');
    $(".select2-selection__rendered").css('min-width','150px');
    $(".select2-search__field").css('min-width','150px');

    var timeout = null;
    $(document).on('keyup', '#search', function () {
        clearTimeout(timeout);
        var url     = $(this).data('url');
        var keyword = $(this).val();
        var item_per_page = $('.selectpage').val()
        timeout = setTimeout(function (){
            $.ajax({
                url     : url,
                type    : "get",
                data    :{
                    keyword         : keyword,
                    item_per_page   : item_per_page
                },
                success : function(data){
                    $('.table_products').html(data);
                }
            })
        }, 1000);
    });

    $(document).on('change', '.selectpage', function () {
        clearTimeout(timeout);
        var url     = $(this).data('url');
        var keyword = $('#search').val();
        var item_per_page = $(this).val();
        timeout = setTimeout(function (){
            $.ajax({
                url     : url,
                type    : "get",
                data    :{
                    keyword         : keyword,
                    item_per_page : item_per_page
                },
                success : function(data){
                    $('.table_products').html(data);
                }
            })
        }, 1000);
    });

    $(document).ajaxComplete(function (){
        $('.pagination li a').click(function (e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url : url,
                success : function(data){
                    $('.table_products').html(data);
                }
            });
        });
    });
});


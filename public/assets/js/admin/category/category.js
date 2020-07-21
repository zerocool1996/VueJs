$(document).ready(function() {
    $('.products').select2({
        placeholder: 'Chọn sản phẩm/ tìm kiếm',
        allowClear: true,
    });
    $(".select2-search__field").css('min-width','200px');
    $(".select2-selection__rendered").css('width','auto');
});

$(document).ready(function() {
    $('.filter-link').on('click', function(e) {
        e.preventDefault();

        const filter = $(this).data('filter');
        $.ajax({
            url : 'dashboard.php',
            data : {filter: filter},
            type: 'GET',
            success: function (result) {
                $("html").html(result);
                $("body").html(result);
            }
        });
    })
});
$(document).ready(function() {
    $('.filter-link').on('click', function(e) {
        e.preventDefault();

        const filter = $(this).data('filter');
        $.ajax({
            url : 'dashboard.php',
            data : {filter: filter, _: new Date().getTime()},
            type: 'GET',
            success: function (result) {
                // $("html").html(result);
                $("body").html(result);
                console.log(result);
            }
        });
    })
});
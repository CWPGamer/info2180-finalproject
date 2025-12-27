document.addEventListener("DOMContentLoaded",function(){
    const container = document.getElementById('main-area');

    $("#login").submit(function(e) {
        e.preventDefault();
        $.ajax ({ 
            url: 'php\\process_login.php',
            data: $(this).serialize(),
            type: 'POST',
            success: function(result)
            {
                console.log(result);
                document.getElementById("message").innerText = result;
                if (result === "Success"){
                    window.location.assign('dashboard.php');
                }
            }
        });
    });

    let j = document.getElementById('main-area');
});

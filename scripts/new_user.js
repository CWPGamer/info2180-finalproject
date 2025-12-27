"use strict";

document.addEventListener("DOMContentLoaded", () => {
    $("#add_user").submit(function(e) {
        console.log
        e.preventDefault();
        $.ajax ({ 
            url: 'php\\process_new_user.php',
            data: $(this).serialize(),
            type: 'POST',
            success: function(result)
            {
                document.getElementById("message").innerText = result;
            }
        });
    });    
});
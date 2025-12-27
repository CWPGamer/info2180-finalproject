"use strict";

document.addEventListener("DOMContentLoaded", () => {
    $("#note_form").submit(function(e) {
        e.preventDefault();
        $.ajax ({ 
            url: 'View_Contact.php',
            data: $(this).serialize(),
            type: 'POST',
            success: function(result)
            {
                console.log(result);
                // document.getElementById("message").innerText = result;
                if (result === "Success"){
                    // window.location.assign('dashboard.php');
                }
            }
        });
    });    
});
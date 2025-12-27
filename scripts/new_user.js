"use strict";

console.log("Yo");

document.addEventListener("DOMContentLoaded", () => {
    // let firstname = document.getElementById("firstname");
    // let lastname = document.getElementById("lastname");
    // let email = document.getElementById("email");
    // let password = document.getElementById("password");

    // console.log(firstname.getAttribute('value'), lastname.getAttribute('value'), email.getAttribute('value'), password.getAttribute('value'));
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
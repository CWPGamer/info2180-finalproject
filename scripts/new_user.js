console.log("Yo");

document.addEventListener("DOMContentLoaded", () => {
    let firstname = document.getElementById("firstname");
    let lastname = document.getElementById("lastname");
    let email = document.getElementById("email");
    let password = document.getElementById("password");

    console.log(firstname.getAttribute('value'), lastname.getAttribute('value'), email.getAttribute('value'), password.getAttribute('value'));
});
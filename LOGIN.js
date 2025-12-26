document.addEventListener("DOMContentLoaded",function(){
    let form= document.querySelector(".form-group form");

    form.addEventListener("login", function(event){
        event.preventDefault();
        
        form.reset();
        
    });
});
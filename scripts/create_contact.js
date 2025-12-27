document.getElementById("contactForm").addEventListener("submit", function (e) {
  const firstname = this.firstname.value.trim();
  const lastname  = this.lastname.value.trim();
  const email     = this.email.value.trim();
  const telephone = this.telephone.value.trim();
  const company   = this.company.value.trim();
  const assigned  = this.assigned_to.value;
  
  e.preventDefault();
  if (firstname === "" || lastname === "" || email === "" || telephone === "" || company === "" || assigned === "") {
    alert("Please fill out all required fields.");
  }
  else{
    $.ajax ({ 
      url: 'create_contact.php',
      data: $(this).serialize(),
      type: 'POST',
      success: function(result)
      {
        $('#message').text(result);
        // document.getElementById("message").innerText = result;
        // console.log(result);
        // document.getElementById("message").innerText = result;
      }
    });  
  }
});

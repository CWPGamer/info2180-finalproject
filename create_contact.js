document.getElementById("contactForm").addEventListener("submit", function (e) {
  const firstname = this.firstname.value.trim();
  const lastname  = this.lastname.value.trim();
  const email     = this.email.value.trim();
  const telephone = this.telephone.value.trim();
  const company   = this.company.value.trim();
  const assigned  = this.assigned_to.value;

  if (firstname === "" || lastname === "" || email === "" || telephone === "" || company === "" || assigned === "") {
    e.preventDefault();
    alert("Please fill out all required fields.");
  }
});

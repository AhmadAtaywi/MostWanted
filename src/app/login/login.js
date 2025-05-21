document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  document.getElementById("loginBtn").addEventListener("click", (e) => {
    e.preventDefault();
    const email = document.getElementById("logemail").value.trim();
    const pass = document.getElementById("logpass").value.trim();
    if (!email || !pass) return alert("Please fill both email and password.");

    const emailRe = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
    if (!emailRe.test(email)) {
      return alert("Please enter a valid email (e.g. user@domain.com).");
    }
  });
});

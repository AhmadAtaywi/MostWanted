document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const loginData = {
      email: loginForm.querySelector('input[type="email"]').value,
      password: loginForm.querySelector('input[type="password"]').value,
    };

    console.log("Login Data:", loginData);
  });

  registerForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const registerData = {
      name: registerForm.querySelector('input[type="text"]').value,
      email: registerForm.querySelector('input[type="email"]').value,
      password: registerForm.querySelector('input[type="password"]').value,
    };

    console.log("Register Data:", registerData);
  });
});

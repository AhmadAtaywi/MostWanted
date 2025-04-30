// document.addEventListener('DOMContentLoaded', () => {
//   const params = new URLSearchParams(window.location.search);
//   const paramRedirect = params.get('redirect');

//   const fallback = '/src/app/home/home.html';

//   const redirectUrl = paramRedirect || fallback;

//   document.getElementById('loginBtn').addEventListener('click', e => {
//     e.preventDefault();
//     const email = document.getElementById('logemail').value.trim();
//     const pass  = document.getElementById('logpass').value.trim();
//     if (!email || !pass) return alert('Please fill both email and password.');

//   const emailRe = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
//   if (!emailRe.test(email)) {
//     return alert('Please enter a valid email (e.g. user@domain.com).');
//   }

//     localStorage.setItem('authToken', 'MOCKED_TOKEN');
//     window.location.href = redirectUrl;
//   });
// });


document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const redirectUrl = params.get('redirect') || '/src/app/home/home.html';

  // grab the modal once
  const errorModalEl = document.getElementById('errorModal');
  const errorMsgEl   = document.getElementById('errorMsg');
  // create a Bootstrap Modal instance
  const errorModal   = new bootstrap.Modal(errorModalEl);

  document.getElementById('loginBtn').addEventListener('click', e => {
    e.preventDefault();
    const email = document.getElementById('logemail').value.trim();
    const pass  = document.getElementById('logpass').value.trim();

    // 1) empty check
    if (!email || !pass) {
      errorMsgEl.textContent = 'Please fill both email and password fields.';
      return errorModal.show();
    }

    // 2) format check
    const emailRe = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
    if (!emailRe.test(email)) {
      errorMsgEl.textContent = 'Please enter a valid email (e.g. user@domain.com).';
      return errorModal.show();
    }

    // 3) mock login success
    localStorage.setItem('authToken', 'MOCKED_TOKEN');
    window.location.href = redirectUrl;
  });
});
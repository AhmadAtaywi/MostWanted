document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const paramRedirect = params.get('redirect');

  const fallback = '/MostWanted/src/app/home/home.php';

  const redirectUrl = paramRedirect || fallback;

  document.getElementById('loginBtn').addEventListener('click', e => {
    e.preventDefault();
    const email = document.getElementById('logemail').value.trim();
    const pass  = document.getElementById('logpass').value.trim();
    if (!email || !pass) return alert('Please fill both email and password.');

  const emailRe = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
  if (!emailRe.test(email)) {
    return alert('Please enter a valid email (e.g. user@domain.com).');
  }

  
    localStorage.setItem('authToken', 'MOCKED_TOKEN');
    window.location.href = redirectUrl;
  });
});
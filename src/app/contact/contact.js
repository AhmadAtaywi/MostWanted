document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    form.addEventListener('submit', e => {
      e.preventDefault();
      const data = {
        name:    form.querySelector('input[placeholder="Your Name"]').value,
        email:   form.querySelector('input[placeholder="Your Email"]').value,
        subject: form.querySelector('input[placeholder="Subject"]').value,
        message: form.querySelector('textarea[placeholder="Message"]').value
      };
      console.log('Contact form submission:', data);
      form.reset();
    });
  });
document.addEventListener('DOMContentLoaded', () => {
    const raw = sessionStorage.getItem('selectedCar');
    if (!raw) return window.location.replace('../home/home.html');
    const car = JSON.parse(raw);
    console.log(car);

    const imgEl = document.getElementById('car-img');
    imgEl.style.backgroundImage = `url(${car.imgUrl})`;
    document.getElementById('car-category').textContent = car.category;
    document.getElementById('car-name').textContent     = car.name;
    document.getElementById('car-price').textContent    = car.price;

    const statMap = {
      'flaticon-dashboard': car.mileage,
      'flaticon-pistons':   car.transmission,
      'flaticon-car-seat':  car.seats,
      'flaticon-backpack':  car.luggage,
      'flaticon-diesel':    car.fuel
    };

    Object.entries(statMap).forEach(([iconClass, value]) => {
      const iconEl = document.querySelector(`.${iconClass}`);
      if (!iconEl) return;
      const span = iconEl
        .closest('.media')
        .querySelector('h3.heading span');
      if (span) span.textContent = value;
    });

    const columns = document.querySelectorAll('#pills-description .features');
    if (Array.isArray(car.features) && car.features.length) {
      columns.forEach(ul => ul.innerHTML = '');
      car.features.forEach((feat, i) => {
        const li = document.createElement('li');
        li.className = 'check';
        li.innerHTML = `<span class="ion-ios-checkmark"></span>${feat}`;
        columns[i % columns.length].appendChild(li);
      });
    }
  });

  fetch('../partials/nav.html')
  .then(r => r.text())
  .then(html => {
    document.getElementById('nav-placeholder').innerHTML = html;
    initNav();
  })
  .catch(err => console.error('Nav load failed:', err));

  fetch('../partials/footer/footer.html')
  .then(r => r.text())
  .then(html => {
    document.getElementById('footer-placeholder').innerHTML = html;
  })
  .catch(err => console.error('Footer load failed:', err));
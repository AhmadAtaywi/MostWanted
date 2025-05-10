document.addEventListener("DOMContentLoaded", () => {
  const raw = sessionStorage.getItem("selectedCar");
  if (!raw) {
    return window.location.replace("../home/home.html");
  }
  const car = JSON.parse(raw);
  console.log("Loaded car:", car);

  document.getElementById("car-img").style.backgroundImage = `url(${car.img})`;
  document.getElementById("car-category").textContent = car.category;
  document.getElementById("car-name").textContent = car.name;
  document.getElementById("car-price").textContent = `$${car.price} /day`;

  const stats = {
    "car-mileage": car.mileage || "",
    "car-transmission": car.transmission || "",
    "car-seats": car.seats || "",
    "car-luggage": car.luggage || "",
    "car-fuel": car.fuel || "",
  };
  Object.entries(stats).forEach(([id, val]) => {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  });

  const features = Array.isArray(car.features) ? car.features : [];
  features.forEach((feat, i) => {
    const ul = document.getElementById(`feat-col-${i % 3}`);
    if (!ul) return;
    const li = document.createElement("li");
    li.className = "check";
    li.innerHTML = `<span class="ion-ios-checkmark"></span> ${feat}`;
    ul.appendChild(li);
  });

  const isLoggedIn = !!localStorage.getItem("authToken");
  const bookBtn = document.getElementById("bookNowBtn");
  if (bookBtn) {
    bookBtn.addEventListener("click", (e) => {
      if (!isLoggedIn) {
        e.preventDefault();
        const here = window.location.pathname + window.location.search;
        window.location.href = `../login/login.html?redirect=${encodeURIComponent(
          here
        )}`;
      }
    });
  }
  const cancelBtn = document.getElementById("bookingCancelBtn");
  cancelBtn.addEventListener("click", () => {
    form.reset();
    uploadGroup.style.display = "none";
  });

  const form = document.getElementById("bookingForm");
  const pickupLocationGroup = document.getElementById("pickupLocationGroup");

  form.querySelectorAll('input[name="pickupType"]').forEach((radio) => {
    radio.addEventListener("change", () => {
      pickupLocationGroup.style.display =
        radio.value === "delivery" ? "block" : "none";
    });
  });

  const uploadGroup = document.getElementById("uploadGroup");
  if (form) {
    form.querySelectorAll('input[name="service"]').forEach((radio) => {
      radio.addEventListener("change", () => {
        uploadGroup.style.display = radio.value === "s5" ? "block" : "none";
      });
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const data = {
        carId: car.id,
        fullName: form.fullName.value,
        email: form.emailAddr.value,
        phone: form.phoneNum.value,
        pickupLocation:
          form.pickupType.value === "delivery"
            ? form.pickupLocation.value
            : null,
        service: form.service.value,
        imageFile: form.uploadImg.files[0] || null,
      };
      console.log("Booking payload:", data);
      $("#bookingModal").modal("hide");
      $("#thankYouModal").modal("show");
    });
  }

  const basePrice = Number(car.price);

  const surchargeMap = {
    s1: 0.04,
    s2: 0.05,
    s3: 0.07,
    s4: 0.15,
    s5: 0.18,
  };

  const priceGroup = document.getElementById("priceGroup");
  const computedPrice = document.getElementById("computedPrice");

  form.querySelectorAll('input[name="service"]').forEach((radio) => {
    radio.addEventListener("change", () => {
      uploadGroup.style.display = radio.value === "s5" ? "block" : "none";

      const extraPct = surchargeMap[radio.value] || 0;
      const total = basePrice * (1 + extraPct);
      computedPrice.value = `$${total.toFixed(2)}`;
      priceGroup.style.display = "block";
    });
  });
});

fetch("../partials/nav.html")
  .then((r) => r.text())
  .then((html) => {
    document.getElementById("nav-placeholder").innerHTML = html;
    initNav();
  })
  .catch(console.error);

fetch("../partials/footer/footer.html")
  .then((r) => r.text())
  .then((html) => {
    document.getElementById("footer-placeholder").innerHTML = html;
  })
  .catch(console.error);

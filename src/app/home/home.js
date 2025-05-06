document.addEventListener("DOMContentLoaded", () => {
  const isLoggedIn = !!localStorage.getItem("authToken");

  ["nav-pricing", "nav-cars"].forEach((id) => {
    const li = document.getElementById(id);
    if (!li) return;

    const link = li.querySelector("a");
    link.addEventListener("click", (e) => {
      if (!isLoggedIn) {
        e.preventDefault();
        const targetPage = link.getAttribute("href");
        const redirectPath = `../home/${targetPage}`;
        window.location.href = `../login/login.html?redirect=${encodeURIComponent(
          redirectPath
        )}`;
      }
    });
  });

  ["nav-login", "nav-register"].forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.classList.toggle("d-none", isLoggedIn);
  });
  ["nav-profile", "nav-logout"].forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.classList.toggle("d-none", !isLoggedIn);
  });

  const logoutEl = document.getElementById("nav-logout");
  if (logoutEl) {
    logoutEl.addEventListener("click", (e) => {
      e.preventDefault();
      localStorage.removeItem("authToken");
      window.location.reload();
    });
  }
});

document.querySelectorAll(".details-btn a").forEach((link) => {
  link.addEventListener("click", (e) => {
    const wrap = link.closest(".car-wrap");
    if (!wrap || !wrap.dataset.name) {
      return;
    }

    e.preventDefault();
    const car = {
      name: wrap.dataset.name,
      category: wrap.dataset.category,
      price: wrap.dataset.price,
      imgUrl: wrap.dataset.img,
      mileage: wrap.dataset.mileage,
      transmission: wrap.dataset.transmission,
      seats: wrap.dataset.seats,
      luggage: wrap.dataset.luggage,
      fuel: wrap.dataset.fuel,
      features: JSON.parse(wrap.dataset.features || "[]"),
    };
    sessionStorage.setItem("selectedCar", JSON.stringify(car));
    window.location.href = "../car-details/car-details.html";
  });
});

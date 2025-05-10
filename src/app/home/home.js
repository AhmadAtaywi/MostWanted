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

  const regions = [
    { name: "Amman", img: "../../images/amman.png" },
    { name: "Irbid", img: "../../images/irbid.jpg" },
    { name: "Aqaba", img: "../../images/aqaba.jpg" },
  ];

  const container = document.getElementById("region-list");
  container.innerHTML = "";

  regions.forEach((region) => {
    const col = document.createElement("div");
    col.className = "col-md-4";
    col.innerHTML = `
      <div class="location-card ftco-animate" style="cursor:pointer">
        <div class="img rounded"
             style="background-image:url(${region.img});
                    height:200px;
                    background-size:cover">
        </div>
        <h3 class="mt-3 test1">${region.name}</h3>
      </div>
    `;
    col.querySelector(".location-card").addEventListener("click", () => {
      const href = `../cars/cars.html?location=${encodeURIComponent(
        region.name
      )}`;
      if (!isLoggedIn) {
        window.location.href = `../login/login.html?redirect=${encodeURIComponent(
          href
        )}`;
      } else {
        window.location.href = href;
      }
    });
    container.appendChild(col);
  });
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

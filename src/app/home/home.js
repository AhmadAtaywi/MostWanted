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

  const topCars = [
    {
      name: "Mercedes Grand Sedan",
      category: "Chevrolet",
      price: 120,
      imgUrl: "../../images/car-1.jpg",
      mileage: "40,000",
      transmission: "Automatic",
      seats: "5 Adults",
      luggage: "3 Bags",
      fuel: "Petrol",
      features: ["Air Conditioning", "GPS", "Bluetooth"],
    },
    {
      name: "Range Rover",
      category: "Crossover SUV",
      price: 95,
      imgUrl: "../../images/car-2.jpg",
      mileage: "30,000",
      transmission: "Automatic",
      seats: "5 Adults",
      luggage: "2 Bags",
      fuel: "Diesel",
      features: ["Sunroof", "Parking Sensors", "Heated Seats"],
    },
    {
      name: "Ford Fusion",
      category: "Fusion",
      price: 70,
      imgUrl: "../../images/ford.jpeg",
      mileage: "25,000",
      transmission: "Manual",
      seats: "5 Adults",
      luggage: "4 Bags",
      fuel: "Petrol",
      features: ["Bluetooth", "USB Charger", "Cruise Control"],
    },
  ];

  const listContainer = document.getElementById("top-cars-list");
  listContainer.innerHTML = "";

  topCars.forEach((car) => {
    const col = document.createElement("div");
    col.className = "col-md-4";

    col.innerHTML = `
    <div class="car-wrap rounded ftco-animate" 
         data-name="${car.name}"
         data-category="${car.category}"
         data-price="$${car.price} /day"
         data-img="${car.imgUrl}"
         data-mileage="${car.mileage}"
         data-transmission="${car.transmission}"
         data-seats="${car.seats}"
         data-luggage="${car.luggage}"
         data-fuel="${car.fuel}"
         data-features='${JSON.stringify(car.features)}'>
      <div class="img rounded d-flex align-items-end" 
           style="background-image: url(${
             car.imgUrl
           }); height:200px; background-size:cover"></div>
      <div class="text">
        <h2 class="mb-0">${car.name}</h2>
        <div class="d-flex mb-3">
          <span class="cat">${car.category}</span>
          <p class="price ml-auto">$${car.price}<span>/day</span></p>
        </div>
        <p class="d-flex mb-0 d-block details-btn">
          <a href="#" class="btn btn-secondary py-2 ml-1">Details</a>
        </p>
      </div>
    </div>
  `;

    listContainer.appendChild(col);
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

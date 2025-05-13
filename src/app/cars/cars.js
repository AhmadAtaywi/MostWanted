document.addEventListener("DOMContentLoaded", () => {
  const regions = [
    { name: "Amman", img: "../../images/amman.png" },
    { name: "Irbid", img: "../../images/irbid.jpg" },
    { name: "Aqaba", img: "../../images/aqaba.jpg" },
    { name: "Zarqa", img: "../../images/zarqa.jpg" },
    { name: "Salt", img: "../../images/salt.jpeg" },
  ];
  const cars = [
    {
      car_type: "Toyota",
      car_model: "camry",
      model_year: 2023,
      city_name: "Amman",
      description:
        "The Toyota Camry is a popular family sedan known for excellent reliability …",
      color: "gray",
      fuel: "hybrid",
      seats: 5,
      price: 120,
      img: "../../images/camry2023.jpg",
      detailsLink: "../car-details/car-details.html",
      available_time: [
        "2025-05-15",
        "2025-05-16",
        "2025-05-17",
        "2025-05-18",
        "2025-05-19",
        "2025-05-20",
        "2025-05-21",
        "2025-05-22",
        "2025-05-23",
        "2025-05-24",
      ],
    },
    {
      car_type: "ford",
      car_model: "fusion",
      model_year: 2014,
      city_name: "Amman",
      description:
        "The test test test is a popular family sedan known for excellent reliability …",
      color: "black",
      fuel: "hybrid",
      seats: 5,
      price: 130,
      img: "../../images/ford.jpeg",
      detailsLink: "../car-details/car-details.html",
      available_time: [
        "2025-05-15",
        "2025-05-16",
        "2025-05-17",
        "2025-05-18",
        "2025-05-19",
        "2025-05-20",
        "2025-05-21",
        "2025-05-22",
        "2025-05-23",
        "2025-05-24",
      ],
    },
    {
      car_type: "BMW",
      car_model: "M3",
      model_year: 2001,
      city_name: "Zarqa",
      description: "3.2-liter inline-6 (S54 engine) with 333 hp at 7,900 rpm …",
      color: "gray & blue",
      fuel: "petrol",
      seats: 5,
      price: 150,
      img: "../../images/bmw-m3.jpg",
      detailsLink: "../car-details/car-details.html",
      available_time: ["2025-05-16", "2025-05-17"],
    },
  ];

  const container = document.getElementById("car-list");
  if (!container) {
    console.error("#car-list not found");
    return;
  }
  container.innerHTML = "";

  const params = new URLSearchParams(window.location.search);
  const selectedRegion = params.get("location");

  if (!selectedRegion) {
    regions.forEach((region) => {
      const col = document.createElement("div");
      col.className = "col-md-4 location";
      col.innerHTML = `
          <div class="location-card ftco-animate" style="cursor:pointer">
            <div class="img rounded"
                 style="background-image:url(${region.img});height:200px;background-size:cover">
            </div>
            <h3 class="mt-3">${region.name}</h3>
          </div>
        `;
      col.querySelector(".location-card").addEventListener("click", () => {
        window.location.search = `?location=${encodeURIComponent(region.name)}`;
      });
      container.appendChild(col);
    });
  } else {
    document.querySelector(".bread").textContent = `Cars in ${selectedRegion}`;

    const filtered = cars.filter((c) => c.city_name === selectedRegion);
    if (filtered.length === 0) {
      container.innerHTML = `<p>No cars found in ${selectedRegion}.</p>`;
      return;
    }

    filtered.forEach((car) => {
      const col = document.createElement("div");
      col.className = "col-md-4 cars";
      col.innerHTML = `
    <div class="car-wrap rounded ftco-animate">
      <div class="img rounded d-flex align-items-end"
           style="background-image:url(${car.img});height:200px;background-size:cover">
      </div>
      <div class="text">
        <h2 class="mb-0">
          ${car.car_type} ${car.car_model} <small>(${car.model_year})</small>
        </h2>
        <p class="mb-2">${car.description}</p>
        <div class="d-flex mb-3">
          <span class="cat">
            ${car.color} &bull; ${car.fuel} &bull; ${car.seats} seats
          </span>
          <p class="price ml-auto">
            $${car.price} <span>/day</span>
          </p>
        </div>
        <p class="d-flex mb-0 d-block justify-content-end">
          <a href="#" class="btn btn-secondary py-2 ml-1 details-btn">Details</a>
        </p>
      </div>
    </div>
  `;
      container.appendChild(col);

      col.querySelector(".details-btn").addEventListener("click", (e) => {
        e.preventDefault();
        sessionStorage.setItem("selectedCar", JSON.stringify(car));
        window.location.href = car.detailsLink;
      });
    });
  }
});

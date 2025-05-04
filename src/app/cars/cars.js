document.addEventListener("DOMContentLoaded", () => {

  const regions = [
    { name: "Amman", img: "../../images/amman.png" },
    { name: "Irbid", img: "../../images/irbid.jpg" },
    { name: "Aqaba", img: "../../images/aqaba.jpg" },
  ];
  const cars = [
    {
      id: "c1",
      name: "Merc Grand Sedan",
      category: "Cheverolet",
      price: 120,
      img: "../../images/car-1.jpg",
      location: "Amman",
      mileage: "40,000",
      transmission: "Automatic",
      seats: "2 Adults",
      luggage: "3 Bags",
      fuel: "Petrol",
      features: ["Air Conditioning", "GPS", "Bluetooth"],
      detailsLink: "../car-details/car-details.html",
    },
    {
      id: "c2",
      name: "Range Rover",
      category: "Crossover SUV",
      price: 95,
      img: "../../images/car-2.jpg",
      location: "Irbid",
      mileage: "30,000",
      transmission: "Automatic",
      seats: "5 Adults",
      luggage: "2 Bags",
      fuel: "Diesel",
      features: ["Sunroof", "Parking Sensors", "Heated Seats"],
      detailsLink: "../car-details/car-details.html",
    },
    {
      id: "c3",
      name: "Ford Fusion",
      category: "Fusion",
      price: 70,
      img: "../../images/ford.jpeg",
      location: "Aqaba",
      mileage: "25,000",
      transmission: "Manual",
      seats: "5 Adults",
      luggage: "4 Bags",
      fuel: "Petrol",
      features: ["Bluetooth", "USB Charger", "Cruise Control"],
      detailsLink: "../car-details/car-details.html",
    },
    {
      id: "c4",
      name: "BMW M3 ",
      category: "GTR",
      price: 150,
      img: "../../images/bmw-m3.jpg",
      location: "Aqaba",
      mileage: "200,000",
      transmission: "Manual",
      seats: "2 Adults",
      luggage: "4 Bags",
      fuel: "Petrol",
      features: ["Bluetooth", "USB Charger", "Cruise Control"],
      detailsLink: "../car-details/car-details.html",
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
      col.className = "col-md-4";
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

    const filtered = cars.filter((c) => c.location === selectedRegion);
    if (filtered.length === 0) {
      container.innerHTML = `<p>No cars found in ${selectedRegion}.</p>`;
      return;
    }

    filtered.forEach((car) => {
      const col = document.createElement("div");
      col.className = "col-md-4";
      col.innerHTML = `
          <div class="car-wrap rounded ftco-animate">
            <div class="img rounded d-flex align-items-end"
                  style="background-image:url(${car.img});height:200px;background-size:cover">
            </div>
            <div class="text">
              <h2 class="mb-0">${car.name}</h2>
              <div class="d-flex mb-3">
                <span class="cat">${car.category}</span>
                <p class="price ml-auto">$${car.price} <span>/day</span></p>
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

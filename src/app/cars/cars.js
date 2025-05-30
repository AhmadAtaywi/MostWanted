document.addEventListener("DOMContentLoaded", () => {
  const regions =
    typeof cities !== "undefined" && Array.isArray(cities)
      ? cities.map((city) => ({
          name: city,
          img: `../../images/${city.toLowerCase()}.jpg`,
        }))
      : [
          { name: "Amman", img: "../../images/amman.jpg" },
          { name: "Zarqa", img: "../../images/zarqa.jpg" },
          { name: "Irbid", img: "../../images/irbid.jpg" },
          { name: "Aqaba", img: "../../images/aqaba.jpg" },
          { name: "Salt", img: "../../images/salt.jpg" },
          { name: "Madaba", img: "../../images/madaba.jpg" },
          { name: "Karak", img: "../../images/karak.jpg" },
          { name: "Tafilah", img: "../../images/tafilah.jpg" },
          { name: "maan", img: "../../images/maan.jpg" },
          { name: "Jarash", img: "../../images/jarash.jpg" },
          { name: "ajloun", img: "../../images/ajloun.jpg" },
        ];

  const cars =
    typeof carsDetails !== "undefined" && Array.isArray(carsDetails)
      ? carsDetails.map((car) => ({
          car_id: car.car_id || "N/A",
          car_type: car.car_type || "Unknown",
          car_model: car.car_model || "Unknown",
          model_year: car.model_year || "N/A",
          city_name: car.city_name || "Unknown",
          description: car.description || "No description available",
          color: car.color || "Unknown",
          fuel: car.fuel || "Unknown",
          seats: car.seats || 0,
          price: car.price || 0,
          img: processImageUrl(car.img) || "../../images/default.jpg",
          available_time: car.available_time || [],
          available: car.available,
        }))
      : []; // Fallback empty array if no data

  function processImageUrl(imgUrl) {
    if (!imgUrl) return "../../images/default-car.jpg";

    try {
      if (imgUrl.includes("google.com/imgres")) {
        const extractedUrl = new URL(imgUrl).searchParams.get("imgurl");
        if (extractedUrl) return extractedUrl;
      }

      new URL(imgUrl);
      return imgUrl;
    } catch (e) {
      return "../../images/default-car.jpg";
    }
  }

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
          <div class="img rounded" style="background-image:url(${region.img});height:200px;background-size:cover"></div>
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

    const controlPanel = document.createElement("div");
    controlPanel.className = "col-12 mb-4";
    controlPanel.innerHTML = `
      <div class="form-inline justify-content-end">
        <input type="text" class="form-control mb-2 mr-sm-2" id="searchInput" placeholder="Search by name">
        <select id="sortSelect" class="form-control mb-2">
          <option value="">Sort by</option>
          <option value="name-asc">Name A-Z</option>
          <option value="name-desc">Name Z-A</option>
          <option value="price-asc">Price Low to High</option>
          <option value="price-desc">Price High to Low</option>
        </select>
      </div>
    `;
    container.appendChild(controlPanel);

    const carList = document.createElement("div");
    carList.className = "row test";
    container.appendChild(carList);

    const renderCars = (list) => {
      console.log("inside renderCars");
      carList.innerHTML = "";
      list.forEach((car) => {
        const col = document.createElement("div");
        col.className = "col-md-4 cars";
        col.innerHTML = `
      <div class="car-wrap rounded ftco-animate">
        <div class="img rounded d-flex align-items-end" style="background-image:url(${
          car.img
        });height:200px;background-size:cover;background-position:center"></div>
        <div class="text">
          <h2 class="mb-0">${car.car_type} ${car.car_model} <small>(${
          car.model_year
        })</small></h2>
          <p class="mb-2 car-description">${car.description}</p>
          <div class="d-flex mb-3">
            <span class="cat">${car.color} • ${car.fuel} • ${
          car.seats
        } seats</span>
            <p class="price ml-auto">JD ${car.price} <span>/day</span></p>
          </div>
          <div class="d-flex mb-3">
            <span class="availability">Available Date: ${
              car.available_time && car.available == "true"
                ? new Date(car.available_time).toISOString().split("T")[0]
                : "Not available"
            }</span>
          </div>
          <p class="d-flex mb-0 d-block justify-content-end">
            <a href="#" class="btn btn-secondary py-2 ml-1 details-btn">Details</a>
          </p>
        </div>
      </div>
    `;
        col.querySelector(".details-btn").addEventListener("click", (e) => {
          e.preventDefault();
          sessionStorage.setItem("selectedCar", JSON.stringify(car));
          window.location.href =
            "/MostWanted/src/app/car-details/car-details.php";
        });
        carList.appendChild(col);
      });
    };

    let currentSearch = "";
    let currentSort = "";

    const updateDisplay = () => {
      let list = filtered.filter((car) =>
        `${car.car_type} ${car.car_model}`
          .toLowerCase()
          .includes(currentSearch.toLowerCase())
      );

      switch (currentSort) {
        case "name-asc":
          list.sort((a, b) => a.car_type.localeCompare(b.car_type));
          break;
        case "name-desc":
          list.sort((a, b) => b.car_type.localeCompare(a.car_type));
          break;
        case "price-asc":
          list.sort((a, b) => a.price - b.price);
          break;
        case "price-desc":
          list.sort((a, b) => b.price - a.price);
          break;
      }

      renderCars(list);
    };

    const searchInput = document.getElementById("searchInput");
    const sortSelect = document.getElementById("sortSelect");

    searchInput.addEventListener("input", (e) => {
      currentSearch = e.target.value;
      updateDisplay();
    });

    sortSelect.addEventListener("change", (e) => {
      currentSort = e.target.value;
      updateDisplay();
    });

    updateDisplay();
  }
});

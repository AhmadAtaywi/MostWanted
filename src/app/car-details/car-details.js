document.addEventListener("DOMContentLoaded", () => {
  const raw = sessionStorage.getItem("selectedCar");
  if (!raw) {
    return window.location.replace("/MostWanted/src/app/home/home.php");
  }

  const car = JSON.parse(raw);

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

  document.getElementById(
    "car-img"
  ).style.backgroundImage = `url(${processImageUrl(car.img)})`;
  document.getElementById("car-city").textContent =
    car.city_name || "Unknown City";
  document.getElementById("car-name").textContent = `${car.car_type || ""} ${
    car.car_model || ""
  } (${car.model_year || ""})`;
  document.getElementById("car-price").textContent = `JD ${
    car.price || 0
  } /day`;
  document.getElementById("car-description").textContent =
    car.description || "No description available";

  document.getElementById("car-color").textContent = car.color || "Unknown";
  document.getElementById("car-fuel").textContent = car.fuel || "Unknown";
  document.getElementById("car-seats").textContent = car.seats || "N/A";

  const isLoggedIn = !!localStorage.getItem("userEmail");
  const bookBtn = document.getElementById("bookNowBtn");

  if (bookBtn) {
    bookBtn.addEventListener("click", (e) => {
      if (!isLoggedIn) {
        e.preventDefault();
        const here = window.location.pathname + window.location.search;
        window.location.href = `/MostWanted/src/app/login/login.php?redirect=${encodeURIComponent(
          here
        )}`;
      } else if (car.available == "false") {
        alert("This car is currently unavailable for booking.");
        e.preventDefault();
        const here = window.location.pathname + window.location.search;
        window.location.href = `/MostWanted/src/app/cars/cars.php?redirect=${encodeURIComponent(
          here
        )}`;
      }
    });
  }

  const availableDateSelect = document.getElementById("availableDate");
  if (availableDateSelect && car.available_time && car.available == "true") {
    const availableTimes = Array.isArray(car.available_time)
      ? car.available_time
      : [car.available_time];

    availableTimes.forEach((time) => {
      const option = document.createElement("option");
      option.value = time;
      option.textContent = new Date(time).toISOString().split("T")[0];
      availableDateSelect.appendChild(option);
    });
  } else {
    const option = document.createElement("option");
    option.value = "not available";
    option.textContent = "Not available";
    availableDateSelect.appendChild(option);
  }

  const pickupStore = document.getElementById("pickupStore");
  const pickupDelivery = document.getElementById("pickupDelivery");
  const pickupLocationGroup = document.getElementById("pickupLocationGroup");

  if (pickupStore && pickupDelivery && pickupLocationGroup) {
    pickupStore.addEventListener("change", () => {
      pickupLocationGroup.style.display = "none";
    });

    pickupDelivery.addEventListener("change", () => {
      pickupLocationGroup.style.display = "block";
    });
  }

  const basePrice = Number(car.price) || 0;
  const surchargeMap = {
    WeddingCeremony: 0.04,
    CityTransfer: 0.05,
    AirportTransfer: 0.07,
    WholeCityTour: 0.15,
    RentACar: 0.18,
  };

  const priceGroup = document.getElementById("priceGroup");
  const computedPrice = document.getElementById("computedPrice");
  const uploadGroup = document.getElementById("uploadGroup");
  const form = document.getElementById("bookingForm");

  if (form) {
    form.querySelectorAll('input[name="service"]').forEach((radio) => {
      radio.addEventListener("change", () => {
        uploadGroup.style.display =
          radio.value === "RentACar" ? "block" : "none";

        const extraPct = surchargeMap[radio.value] || 0;
        const total = basePrice * (1 + extraPct);
        if (computedPrice) computedPrice.value = `$${total.toFixed(2)}`;
        if (priceGroup) priceGroup.style.display = "block";
      });
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const bookingData = {
        carId: car.car_id || null,
        carType: car.car_type,
        carModel: car.car_model,
        fullName: form.fullName.value,
        email: form.emailAddr.value,
        phone: form.phoneNum.value,
        date: form.availableDate.value,
        pickupType: form.pickupType.value,
        pickupLocation: form.pickupLocation.value || null,
        service: form.service.value,
        price: computedPrice ? computedPrice.value : basePrice,
        drivingLicense: form.uploadImg.files[0]
          ? form.uploadImg.files[0].name
          : null,
      };

      console.log("Booking data JS-line-140:", bookingData);

      setTimeout(() => {
        window.location.href = `/MostWanted/src/app/car-details/car-details.php?bookingData=${JSON.stringify(
          bookingData
        )}`;
      }, 2000);

      $("#bookingModal").modal("hide");
      $("#thankYouModal").modal("show");

      form.reset();
      if (uploadGroup) uploadGroup.style.display = "none";
      if (priceGroup) priceGroup.style.display = "none";
    });
  }

  const cancelBtn = document.getElementById("bookingCancelBtn");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", () => {
      if (form) form.reset();
      if (uploadGroup) uploadGroup.style.display = "none";
      if (priceGroup) priceGroup.style.display = "none";
    });
  }
});

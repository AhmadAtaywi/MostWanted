document.addEventListener("DOMContentLoaded", () => {
  // Get car data from session storage
  const raw = sessionStorage.getItem("selectedCar");
  if (!raw) {
    return window.location.replace("/MostWanted/src/app/home/home.php");
  }

  const car = JSON.parse(raw);

function processImageUrl(imgUrl) {
  // If empty, return default image
  if (!imgUrl) return "../../images/default-car.jpg";

  try {
    // Handle Google Images redirect URLs
    if (imgUrl.includes("google.com/imgres")) {
      const extractedUrl = new URL(imgUrl).searchParams.get("imgurl");
      if (extractedUrl) return extractedUrl;
    }

    // Handle direct URLs
    new URL(imgUrl); // This will throw if invalid URL
    return imgUrl;
  } catch (e) {
    // If URL is invalid, return default
    return "../../images/default-car.jpg";
  }
}

  document.getElementById("car-img").style.backgroundImage = `url(${processImageUrl(car.img)})`;
  document.getElementById("car-city").textContent =
    car.city_name || "Unknown City";
  document.getElementById("car-name").textContent = `${car.car_type || ""} ${
    car.car_model || ""
  } (${car.model_year || ""})`;
  document.getElementById("car-price").textContent = `$${car.price || 0} /day`;
  document.getElementById("car-description").textContent =
    car.description || "No description available";

  // Set car specifications
  document.getElementById("car-color").textContent = car.color || "Unknown";
  document.getElementById("car-fuel").textContent = car.fuel || "Unknown";
  document.getElementById("car-seats").textContent = car.seats || "N/A";

  // Check if user is logged in
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
        // message popup this car is unavailable
        alert("This car is currently unavailable for booking.");
        e.preventDefault();
        const here = window.location.pathname + window.location.search;
        window.location.href = `/MostWanted/src/app/cars/cars.php?redirect=${encodeURIComponent(
          here
        )}`;
      }
    });
  }

  // Populate available dates dropdown
  const availableDateSelect = document.getElementById("availableDate");
  if (availableDateSelect && car.available_time && car.available == "true") {
    // Ensure available_time is an array
    const availableTimes = Array.isArray(car.available_time)
      ? car.available_time
      : [car.available_time]; // Convert string to array if necessary

    availableTimes.forEach((time) => {
      const option = document.createElement("option");
      option.value = time;
      option.textContent = new Date(time).toISOString().split("T")[0]; // Format as YYYY-MM-DD
      availableDateSelect.appendChild(option);
    });
  } else {
    // Fallback if no available times are specified
    const option = document.createElement("option");
    option.value = "not available";
    option.textContent = "Not available";
    availableDateSelect.appendChild(option);
  }

  // Setup pickup location toggle
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

  // Setup price calculation
  const basePrice = Number(car.price) || 0;
  const surchargeMap = {
    WeddingCeremony: 0.04, // Wedding Ceremony
    CityTransfer: 0.05, // City Transfer
    AirportTransfer: 0.07, // Airport Transfer
    WholeCityTour: 0.15, // Whole City Tour
    RentACar: 0.18, // Rent A Car
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
        // setTimeout(() => {
        //   window.location.href = `/MostWanted/src/app/cars/cars.php`;
        // }, 2000);
      }, 2000);

      // const params = new URLSearchParams(window.location.search);
      // if (!params.has("bookingData")) {
      //   const target = `/MostWanted/src/app/cars/cars.php`;
      //   window.location.href = target;
      //   return; // stop running any further code on this page
      // }

      // Here you would typically send the data to your backend
      // For now, we'll just show the thank you modal
      $("#bookingModal").modal("hide");
      $("#thankYouModal").modal("show");

      // Reset form
      form.reset();
      if (uploadGroup) uploadGroup.style.display = "none";
      if (priceGroup) priceGroup.style.display = "none";
    });
  }

  // Cancel button handler
  const cancelBtn = document.getElementById("bookingCancelBtn");
  if (cancelBtn) {
    cancelBtn.addEventListener("click", () => {
      if (form) form.reset();
      if (uploadGroup) uploadGroup.style.display = "none";
      if (priceGroup) priceGroup.style.display = "none";
    });
  }
});

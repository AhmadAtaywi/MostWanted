document.addEventListener("DOMContentLoaded", () => {
  const userEmail = localStorage.getItem("userEmail");
  // bail out if no email
  if (!userEmail) return;

  // only redirect if bookingData is missing
  const params = new URLSearchParams(window.location.search);
  if (!params.has("bookingData")) {
    const target = `/MostWanted/src/app/profile/profile.php?bookingData=${userEmail}`;
    window.location.href = target;
    return;  // stop running any further code on this page
  }
});
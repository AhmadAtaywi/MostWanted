document.addEventListener("DOMContentLoaded", () => {
  const userEmail = localStorage.getItem("userEmail");
  if (!userEmail) return;

  const params = new URLSearchParams(window.location.search);
  if (!params.has("bookingData")) {
    const target = `/MostWanted/src/app/profile/profile.php?bookingData=${userEmail}`;
    window.location.href = target;
    return;
  }
});
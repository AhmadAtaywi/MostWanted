function initNav() {
  const isLoggedIn = !!localStorage.getItem("authToken");

  ["nav-login", "nav-register"].forEach((id) => {
    document.getElementById(id)?.classList.toggle("d-none", isLoggedIn);
  });
  ["nav-profile", "nav-logout"].forEach((id) => {
    document.getElementById(id)?.classList.toggle("d-none", !isLoggedIn);
  });

  document.getElementById("nav-logout")?.addEventListener("click", (e) => {
    e.preventDefault();
    localStorage.removeItem("authToken");
    window.location.reload();
  });

  document.querySelectorAll("a[data-protected]").forEach((a) => {
    a.addEventListener("click", (e) => {
      if (!isLoggedIn) {
        e.preventDefault();
        const intended = a.getAttribute("href");
        window.location.href = `/src/app/login/login.php?redirect=${encodeURIComponent(
          intended
        )}`;
      }
    });
  });

  const current = window.location.pathname;
  document.querySelectorAll("#ftco-nav .nav-item").forEach((li) => {
    const a = li.querySelector("a");
    if (!a) return;

    const href = a.getAttribute("href");
    if (!href || href.startsWith("#")) {
      li.classList.remove("active");
      return;
    }

    if (a.pathname === current) {
      li.classList.add("active");
    } else {
      li.classList.remove("active");
    }
  });
}

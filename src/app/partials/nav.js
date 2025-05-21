function initNav() {
  const isLoggedIn = !!localStorage.getItem("userEmail");
  const userEmail = localStorage.getItem("userEmail") || "";

  ["nav-login", "nav-register"].forEach((id) => {
    document.getElementById(id)?.classList.toggle("d-none", isLoggedIn);
  });
  ["nav-profile", "nav-logout"].forEach((id) => {
    document.getElementById(id)?.classList.toggle("d-none", !isLoggedIn);
  });

  document
    .getElementById("nav-welcome")
    ?.classList.toggle("d-none", !isLoggedIn);

  document.getElementById("nav-logout")?.addEventListener("click", (e) => {
    e.preventDefault();
    localStorage.removeItem("userEmail");
    window.location.reload();
  });

  const welcomeSpan = document.querySelector("#nav-welcome .nav-link");
  if (welcomeSpan) {
    welcomeSpan.textContent = `welcome ${userEmail}`;
  }

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
  console.log(4);
  const profileAnchor = document.querySelector("#nav-profile a");
  if (profileAnchor) {
    console.log(1);
    profileAnchor.addEventListener("click", (e) => {
      e.preventDefault();
      console.log(2);
      const email = localStorage.getItem("userEmail");
      if (!email) {
        window.location.href = "/src/app/login/login.php";
        return;
      }

      const url = new URL(profileAnchor.href, window.location.origin);
      url.searchParams.set("email", email);
      window.location.href = url.toString();
    });
  }
}

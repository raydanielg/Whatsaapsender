(function () {
  ("use strict");

  let windowWidth = window.innerWidth;

  // Show User Information
  const showContactBtn = document.querySelector(".show-contact-sidebar");
  if (showContactBtn && windowWidth < 768) {
    showContactBtn.addEventListener("click", () => {
      const chatLeft = document.querySelector(".chat-left");
      chatLeft.classList.add("open-left-drawer");
      createOverlay();
    });
  }

  // Show User Information
  const showUser = document.querySelector(".show-user-btn");
  if (showUser && windowWidth < 1400) {
    showUser.addEventListener("click", () => {
      const userInfoWrapper = document.querySelector(".user-info-wrapper");
      userInfoWrapper.classList.add("show");
      createOverlay();

      const contactCloser = document.querySelector(".contact-closer");
      if (contactCloser && userInfoWrapper.classList.contains("show")) {
        contactCloser.addEventListener("click", () => {
          userInfoWrapper.classList.remove("show");
          removeOverlay();
        });
      }
    });
  }

  // Create overlay
  function createOverlay() {
    const overlay = document.createElement("div");
    overlay.setAttribute("id", "sidebar-overlay");

    overlay.style.cssText = `
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100vh;
        background: var(--color-dark);
        opacity: 0.2;
        z-index: 0;
        `;
    document.body.appendChild(overlay);

    // Add event listener for the overlay here
    overlay.addEventListener("click", () => {
      const chatLeft = document.querySelector(".chat-left");
      if (chatLeft && chatLeft.classList.contains("open-left-drawer")) {
        chatLeft.classList.remove("open-left-drawer");
      }

      const userInfoWrapper = document.querySelector(".user-info-wrapper");
      if (userInfoWrapper && userInfoWrapper.classList.contains("show")) {
        userInfoWrapper.classList.remove("show");
      }

      removeOverlay();
      sidebarVisible = false;
    });
  }

  // Remove overlay
  function removeOverlay() {
    const sidebarOverlay = document.querySelector("#sidebar-overlay");
    sidebarOverlay && sidebarOverlay.remove();
  }
})();

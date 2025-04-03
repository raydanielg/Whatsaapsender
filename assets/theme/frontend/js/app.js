(function () {
  ("use strict");

  // HTML Root Element
  const rootHtml = document.firstElementChild;
  let windowWidth = window.innerWidth;
  const minWidth = 991;

  const header = document.querySelector(".header");
  if (header) {
    const checkScroll = () => {
      if (window.scrollY > 0) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    };

    window.addEventListener("scroll", checkScroll);

    window.addEventListener("load", checkScroll);
  }

  // Sidebar Start
  function showSidebar() {
    var sidebar = document.querySelector(".sidebar");
    if (sidebar) {
      sidebar.style.transform = "translateX(0%)";
    }
  }

  function hideSidebar() {
    var sidebar = document.querySelector(".sidebar");
    if (sidebar) {
      sidebar.style.transform = "translateX(-100%)";
    }
  }

  function createOverlay() {
    const overlay = document.createElement("div");
    overlay.setAttribute("id", "sidebar-overlay");

    overlay.style.cssText = `
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100vh;
        background: rgb(0 0 0 / 30%);
        z-index: 100;
        `;
    document.body.appendChild(overlay);

    // Add event listener for the overlay here
    overlay.addEventListener("click", () => {
      hideSidebar();
      removeOverlay();
      sidebarVisible = false;
    });
  }

  function removeOverlay() {
    const emailOverlay = document.querySelector("#sidebar-overlay");
    emailOverlay && emailOverlay.remove();
  }

  var sidebarButton = document.querySelector("#menu-btn");
  var sidebarVisible = false;

  if (sidebarButton) {
    sidebarButton.addEventListener("click", () => {
      if (!sidebarVisible) {
        showSidebar();
        createOverlay();
      } else {
        hideSidebar();
        removeOverlay();
      }
      sidebarVisible = !sidebarVisible;
    });
  }

  function handleResize() {
    let windowWidth = window.innerWidth;
    if (windowWidth >= minWidth) {
      showSidebar();
      removeOverlay();
    } else {
      hideSidebar();
      removeOverlay();
    }
  }
  window.addEventListener("resize", handleResize);
  handleResize();
  // Sidebar End

  // Mega Menu
  const menuLinks = document.querySelectorAll(".menu-link");
  if (menuLinks) {
    let windowWidth = window.innerWidth;
    if (windowWidth <= minWidth) {
      menuLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
          event.stopPropagation();

          const icon = link.querySelector("span");
          if (icon) {
            icon.classList.toggle("rotate-180");
          }

          const nextItem = link.nextElementSibling;
          if (nextItem) {
            nextItem.classList.toggle("show");
          }
        });
      });
    }
  }

  // Review slider
  const reviewSlider = document.querySelector(".review-slider");
  if (reviewSlider) {
    new Swiper(reviewSlider, {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".review-next",
        prevEl: ".review-prev",
      },

      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 25,
        },
        1024: {
          slidesPerView: 2,
        },
        1200: {
          slidesPerView: 3,
          spaceBetween: 40,
        },
        1500: {
          slidesPerView: 3,
          spaceBetween: 100,
        },
      },
    });
  }

  // Blog slider
  const gatewaySlider = document.querySelector(".gateway-slider");
  if (gatewaySlider) {
    new Swiper(gatewaySlider, {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".button-next",
        prevEl: ".button-prev",
      },
      breakpoints: {
        320: {
          slidesPerView: 2,
          spaceBetween: 15,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 4,
        },
        1200: {
          slidesPerView: 5,
          spaceBetween: 30,
        },

        1500: {
          slidesPerView: 7,
          spaceBetween: 30,
        },
      },
    });
  }

  // Feature slider
  const featureSlider = document.querySelector(".feature-slider");
  if (featureSlider) {
    new Swiper(featureSlider, {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      centeredSlides: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".review-next",
        prevEl: ".review-prev",
      },

      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 2,
        },
        1200: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },
    });
  }

  // Hover Tabs
  const tabOnHover = document.querySelectorAll(".menu-feature");
  if (tabOnHover) {
    tabOnHover.forEach((itemHover) => {
      itemHover
        .querySelectorAll(".menu-feature-item")
        .forEach(function (tabBtn) {
          var tabTrigger = new bootstrap.Tab(tabBtn);
          tabBtn.addEventListener("mouseenter", function () {
            tabTrigger.show();
          });
        });
    });
  }
})();

(function () {
    "use strict"
    // HTML Root Element
    const rootHtml = document.firstElementChild;
    const maxWidth = 992
    let windowWidth = window.innerWidth;
    let sidebarVisible = false;

    // Sidebar collapse Menu
    if (document.querySelectorAll(".sidebar-menu .collapse")) {
        var collapses = document.querySelectorAll(".sidebar-menu .collapse");
        Array.from(collapses).forEach(function (collapse) {
            // Init collapses
            var collapseInstance = new bootstrap.Collapse(collapse, {
                toggle: false,
            });
            // Hide sibling collapses on `show.bs.collapse`
            collapse.addEventListener("show.bs.collapse", function (e) {
                e.stopPropagation();
                var closestCollapse = collapse.parentElement.closest(".collapse");
                if (closestCollapse) {
                    var siblingCollapses = closestCollapse.querySelectorAll(".collapse");
                    Array.from(siblingCollapses).forEach(function (siblingCollapse) {
                        var siblingCollapseInstance =
                            bootstrap.Collapse.getInstance(siblingCollapse);
                        if (siblingCollapseInstance === collapseInstance) {
                            return;
                        }
                        siblingCollapseInstance.hide();
                    });
                } else {
                    var getSiblings = function (elem) {
                        // Setup siblings array and get the first sibling
                        var siblings = [];
                        var sibling = elem.parentNode.firstChild;
                        // Loop through each sibling and push to the array
                        while (sibling) {
                            if (sibling.nodeType === 1 && sibling !== elem) {
                                siblings.push(sibling);
                            }
                            sibling = sibling.nextSibling;
                        }
                        return siblings;
                    };
                    var siblings = getSiblings(collapse.parentElement);
                    Array.from(siblings).forEach(function (item) {
                        if (item.childNodes.length > 2)
                            item.firstElementChild.setAttribute("aria-expanded", "false");
                        var ids = item.querySelectorAll("*[id]");
                        Array.from(ids).forEach(function (item1) {
                            item1.classList.remove("show");
                            if (item1.childNodes.length > 2) {
                                var val = item1.querySelectorAll("ul li a");
                                Array.from(val).forEach(function (subitem) {
                                    if (subitem.hasAttribute("aria-expanded"))
                                        subitem.setAttribute("aria-expanded", "false");
                                });
                            }
                        });
                    });
                }
            });

            // Hide nested collapses on `hide.bs.collapse`
            collapse.addEventListener("hide.bs.collapse", function (e) {
                e.stopPropagation();
                var childCollapses = collapse.querySelectorAll(".collapse");
                Array.from(childCollapses).forEach(function (childCollapse) {
                    childCollapseInstance = bootstrap.Collapse.getInstance(childCollapse);
                    childCollapseInstance.hide();
                });
            });
        });
    }

    // Sidebar Controller Start
    function setElementAttribute(element, attribute, value) {
        element.setAttribute(attribute, value);
    }

    function showSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.cssText = `transform: translateX(0%) !important;`;
        }
    }

    function hideSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.style.cssText = `transform: translateX(-105%);`;
        }
    }

    function createOverlay() {
        const overlay = document.createElement('div');
        overlay.setAttribute("id", "sidebar-overlay");

        overlay.style.cssText = `
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100vh;
        background: var(--color-dark);
        opacity: 0.2;
        z-index: 99;
        `
        document.body.appendChild(overlay);

        // Add event listener for the overlay here
        overlay.addEventListener("click", () => {
            hideSidebar();
            removeOverlay();
            sidebarVisible = false;
        });
    }

    function removeOverlay() {
        const sidebarOverlay = document.querySelector("#sidebar-overlay")
        sidebarOverlay && sidebarOverlay.remove()
    }

    function toggleSidebar(event) {
        event.stopPropagation();
        if (windowWidth < maxWidth) {
            if (!sidebarVisible) {
                showSidebar();
                createOverlay();
            } else {
                hideSidebar();
                removeOverlay();
            }
            sidebarVisible = !sidebarVisible;
        }
    }

    const sideBarCloser = document.querySelector("#sideBar-closer");
    if (sideBarCloser) {
        sideBarCloser.addEventListener("click", () => {
            event.stopPropagation();
            hideSidebar()
            removeOverlay()
            sidebarVisible = false;
        })
    }

    function handleWindowResize() {
        const newWindowWidth = window.innerWidth;
        if (newWindowWidth !== windowWidth) {
            windowWidth = newWindowWidth;
            if (windowWidth < maxWidth) {
                hideSidebar()
                removeOverlay()
                const sidebar = document.querySelector('.sidebar');
                sidebar.style.cssText = ` transition: unset`;
            } else {
                removeOverlay();
                showSidebar()
            }
        }
    }
    
    const sidebarHandler = document.querySelector("#sidebar-handler");
    sidebarHandler && sidebarHandler.addEventListener("click", toggleSidebar)

    window.addEventListener('resize', handleWindowResize);
    handleWindowResize();


    const menuLinks = document.querySelectorAll(".menu-link");
    if (menuLinks) {
        menuLinks.forEach(link => {
            link.addEventListener("click", (event) => {
                event.stopPropagation();

                // Hide Other elements
                menuLinks.forEach(otherLink => {
                    const otherLinkNextItem = otherLink.nextElementSibling;
                    if (otherLinkNextItem && otherLinkNextItem.classList.contains("show")) {
                        otherLinkNextItem.classList.remove("show");
                        otherLink.classList.remove("active")
                        otherLinkNextItem.style.cssText = `opacity:0; visibility:hidden`;
                    }
                })

                const nextItem = link.nextElementSibling;
                
                if (nextItem) {
                    if (windowWidth >= 1200) {
                        rootHtml.classList.add("menu-active")
                    }

                    nextItem.classList.add("show");
                    link.classList.add("active")
                    nextItem.style.cssText = `opacity:1; visibility:visible`;

                    // Hide on click back-to-menu
                    const backToMenu = nextItem.querySelector(".back-to-menu")
                    backToMenu.addEventListener("click", () => {
                        nextItem.classList.remove("show");
                        link.classList.remove("active")
                        if (rootHtml.classList.contains("menu-active")) {
                            rootHtml.classList.remove("menu-active")
                        }
                    })
                }
            });
        });
    }

    // Hide on click outside the menu item
    const clickHandler = (event) => {
        const subWrapper = document.querySelectorAll(".sub-menu-wrapper");

        if (subWrapper) {
            subWrapper.forEach(subMenuItem => {
                if (!subMenuItem.contains(event.target) && subMenuItem.classList.contains("show")) {
                    subMenuItem.classList.remove("show");
                    if (rootHtml.classList.contains("menu-active")) {
                        rootHtml.classList.remove("menu-active")
                    }

                    // Remove Active class 
                    const prvItem = subMenuItem.previousElementSibling;
                    if (prvItem && prvItem.classList.contains("active")) {
                        prvItem.classList.remove("active");
                    }
                }
            });
        }
    };
    if (windowWidth < 1200) {
        window.addEventListener("click", clickHandler);
    }

    // Sidebar Controller End


    // Default localstroag settings
    // let siteData = {
    //     lang: "en",
    //     dir: "ltl",
    //     dataSidebarMode: "Light",
    //     dataTopbarMode: "Light",
    //     dataTheme: "light",
    // };

    // const setLocalStorageData = (siteData) => {
    //     localStorage.setItem("siteData", JSON.stringify(siteData))
    // };

    // const getLocalStorageData = () => {
    //     const siteDataJSON = localStorage.getItem("siteData");
    //     if (siteDataJSON !== null) {
    //         return JSON.parse(siteDataJSON);
    //     } else {
    //         return siteData;
    //     }
    // };

    // LTR & RTL Features Start
    // function addCSS(cssFile) {
    //     const cssLink = document.querySelector("#bootstrap-css");

    //     if (!cssLink) {
    //         const newCssLink = document.createElement("link");
    //         newCssLink.rel = "stylesheet";
    //         newCssLink.type = "text/css";
    //         newCssLink.href = cssFile;
    //         newCssLink.id = "bootstrap-css";
    //         document.head.appendChild(newCssLink);
    //     } else {
    //         cssLink.href = cssFile;
    //     }
    // }

    // function handleDirection() {
    //     const switcher = document.querySelector('#direction-switcher');
    //     if (switcher) {
    //         switcher.addEventListener('input', (e) => {
    //             const dirMode = e.target.value;
    //             setDirection(dirMode);
    //         });
    //     }

    //     const setDirection = (dirMode) => {
    //         rootHtml.setAttribute("dir", dirMode);

    //         // let cssFile;
    //         // if (dirMode === "rtl") {
    //         //     cssFile = "./assets/css/bootstrap.rtl.min.css";
    //         // } else {
    //         //     cssFile = "./assets/css/bootstrap.min.css";
    //         // }

    //         // addCSS(cssFile);
    //     };

    //     // Initialize CSS based on initial direction
    //     const initialDirMode = rootHtml.getAttribute("dir");
    //     setDirection(initialDirMode);
    // }

    // handleDirection();


    // Theme Features Start
    // const handelTheme = (e) => {
    //     const currentAttribute = rootHtml.getAttribute("data-bs-theme");
    //     const newAttribute = currentAttribute === 'light'
    //         ? 'dark'
    //         : 'light';
    //     setElementAttribute(rootHtml, "data-bs-theme", newAttribute);

    //     const activeIcon = document.querySelector('#theme-toggle > i');
    //     // Update active icon based on theme
    //     activeIcon.className = newAttribute === 'dark' ? 'ri-sun-line' : 'ri-moon-line';

    //     setPreference(newAttribute);
    // };

    // const getColorPreference = () => {
    //     const storedData = getLocalStorageData();
    //     if (storedData) {
    //         return storedData;
    //     } else {
    //         return window.matchMedia('(prefers-color-scheme: dark)').matches
    //             ? 'dark'
    //             : 'light';
    //     }
    // };

    // const setPreference = (theme) => {
    //     const currentData = { ...getLocalStorageData(), dataTheme: theme };
    //     setLocalStorageData(currentData);
    //     reflectPreference(theme);
    // };

    // const reflectPreference = (theme) => {
    //     rootHtml.setAttribute("data-bs-theme", theme);
    //     document
    //         .querySelector('#theme-toggle')
    //         ?.setAttribute('aria-label', theme);
    // };

    // const themeToggle = document.querySelector('#theme-toggle');
    // if (themeToggle) {
    //     themeToggle.addEventListener('click', handelTheme);
    // }


    // sync with system changes
    // window.matchMedia('(prefers-color-scheme: dark)')
    //     .addEventListener('change', ({ matches: isDark }) => {
    //         const currentTheme = isDark ? 'dark' : 'light';
    //         setPreference(currentTheme);
    //     });

    // const isSelectedTheme = () => {
    //     const storedData = getLocalStorageData();
    //     if (storedData) {
    //         const currentTheme = storedData.dataTheme;
    //         rootHtml.setAttribute("data-bs-theme", currentTheme);

    //         const activeIcon = document.querySelector('#theme-toggle > i');
    //         // Update active icon based on theme
    //         activeIcon.className = currentTheme === 'dark' ? 'ri-sun-line dark' : 'light ri-moon-line';
    //     }

    // }

    // if (document.readyState === 'loading') {
    //     window.addEventListener('DOMContentLoaded',
    //         isSelectedTheme
    //     )
    // }


}())
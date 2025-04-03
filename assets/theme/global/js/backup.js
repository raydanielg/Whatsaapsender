(function () {
    "use strict"
    // HTML Root Element
    const rootHtml = document.documentElement;
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

    // Sidebar

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
            link.addEventListener("click", () => {
                event.stopPropagation(); // Stop event propagation 

                // Hide Other elements
                menuLinks.forEach(otherLink => {
                    const otherLinkNextItem = otherLink.nextElementSibling;
                    if (otherLinkNextItem && otherLinkNextItem.classList.contains("show")) {
                        otherLinkNextItem.classList.remove("show");
                        otherLinkNextItem.style.cssText = `opacity:0 ;visibility:hidden`
                        otherLink.classList.remove("active")
                    }
                })

                const nextItem = link.nextElementSibling;
                if (nextItem) {
                    nextItem.classList.add("show");
                    nextItem.style.cssText = `opacity:1; visibility:visible`;
                    link.classList.add("active")

                    // Hide on click back-to-menu
                    const backToMenu = nextItem.querySelector(".back-to-menu")
                    backToMenu.addEventListener("click", () => {
                        nextItem.classList.remove("show");
                        link.classList.remove("active")
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

                    // Remove Active class 
                    const prvItem = subMenuItem.previousElementSibling;

                    if (prvItem && prvItem.classList.contains("active")) {
                        prvItem.classList.remove("active");
                    }
                }
            });
        }
    };
    window.addEventListener("click", clickHandler);
}())
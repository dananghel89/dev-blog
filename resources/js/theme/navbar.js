// Builds the off-canvas mobile menu from the desktop menu (.js-clone-nav) and wires the toggles.
export function initNavbar() {
    const mobileMenu = document.querySelector('.site-mobile-menu');
    const mobileMenuBody = document.querySelector('.site-mobile-menu-body');

    if (!mobileMenu || !mobileMenuBody) {
        return;
    }

    document.querySelectorAll('.js-clone-nav').forEach((nav) => {
        const navCloned = nav.cloneNode(true);
        navCloned.setAttribute('class', 'site-nav-wrap');
        mobileMenuBody.appendChild(navCloned);
    });

    // Turn nested dropdowns of the cloned menu into Bootstrap collapses.
    mobileMenu.querySelectorAll('.has-children').forEach((hasChild, index) => {
        const link = hasChild.querySelector('a');
        const dropdown = hasChild.querySelector('.dropdown');

        if (!dropdown) {
            return;
        }

        const arrow = document.createElement('span');
        arrow.setAttribute('class', 'arrow-collapse collapsed');
        arrow.setAttribute('data-bs-toggle', 'collapse');
        arrow.setAttribute('data-bs-target', `#collapseItem${index}`);
        arrow.setAttribute('role', 'button');
        arrow.setAttribute('aria-label', 'Toggle submenu');
        hasChild.insertBefore(arrow, link);

        dropdown.setAttribute('class', 'collapse');
        dropdown.setAttribute('id', `collapseItem${index}`);
    });

    const toggles = document.querySelectorAll('.js-menu-toggle');

    const setOpen = (open) => {
        document.body.classList.toggle('offcanvas-menu', open);
        toggles.forEach((toggle) => toggle.classList.toggle('active', open));
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            setOpen(!document.body.classList.contains('offcanvas-menu'));
        });
    });

    // Close the menu on any click outside it and outside the toggles.
    document.addEventListener('click', (event) => {
        const insideMenu = mobileMenu.contains(event.target);
        const onToggle = [...toggles].some((toggle) => toggle.contains(event.target));

        if (!insideMenu && !onToggle && document.body.classList.contains('offcanvas-menu')) {
            setOpen(false);
        }
    });
}

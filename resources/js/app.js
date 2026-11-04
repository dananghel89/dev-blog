// Bootstrap 5.3 JS (bundle entry includes Popper via @popperjs/core)
import 'bootstrap';

// Fonts and icon CSS: imported here (not from SCSS) so Vite resolves and emits their font files.
import '@fontsource/work-sans/400.css';
import '@fontsource/work-sans/600.css';
import '@fontsource/work-sans/700.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'aos/dist/aos.css';

import { initNavbar } from './theme/navbar';
import { hidePreloader, initAnimations, refreshAnimations } from './theme/init';

document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initAnimations();
    hidePreloader();
});

// Livewire 4 injects its own script; after it morphs the DOM, let AOS pick up new elements.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morph.updated', () => refreshAnimations());
});

/*!
 * Template Name: Blogy
 * Template Author: Untree.co (https://untree.co/)
 * Template License: https://creativecommons.org/licenses/by/3.0/
 * Modified for Dev Blog: only the AOS setup and the preloader are kept (no sliders, no lightbox).
 */
import AOS from 'aos';

export function initAnimations() {
    AOS.init({
        duration: 800,
        easing: 'slide',
        once: true,
    });
}

// Re-scan for [data-aos] elements after Livewire replaces part of the page.
export function refreshAnimations() {
    AOS.refresh();
}

// Fades out the theme's preloader (#overlayer + .loader), if the layout has one.
// A CSS transition + timer instead of the theme's requestAnimationFrame loop, which stalls in background tabs.
export function hidePreloader() {
    const elements = [document.querySelector('.loader'), document.getElementById('overlayer')].filter(Boolean);

    setTimeout(() => {
        elements.forEach((el) => {
            el.style.transition = 'opacity .3s ease';
            el.style.opacity = '0';
        });
        setTimeout(() => elements.forEach((el) => (el.style.display = 'none')), 300);
    }, 200);
}

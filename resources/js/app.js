import "./libs/trix";
import './bootstrap';
import intersect from '@alpinejs/intersect';

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(intersect);

    window.Alpine.store('theme', {
        current: localStorage.getItem('theme') || 'light',
        toggle() {
            this.current = this.current === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', this.current);
            document.documentElement.classList.toggle('dark-mode-filter', this.current === 'dark');
        }
    });
});

document.addEventListener("trix-file-accept", function(event) {
    event.preventDefault();
});
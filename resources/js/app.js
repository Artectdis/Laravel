import "./libs/trix";
import './bootstrap';
import intersect from '@alpinejs/intersect';

// Register the plugin on the global Alpine instance Livewire provides
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(intersect);
});

document.addEventListener("trix-file-accept", function(event) {
    event.preventDefault();
});
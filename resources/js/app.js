import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

// Register Alpine plugins
Alpine.plugin(focus);
Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.start();

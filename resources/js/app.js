import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import collapse from '@alpinejs/collapse';

import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

import AOS from 'aos';
import 'aos/dist/aos.css';

// Configure Swiper
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);
window.Swiper = Swiper;

// Configure AOS
window.AOS = AOS;

Alpine.plugin(intersect);
Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.start();

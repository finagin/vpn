import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] =
    'TelegramMiniApp ' + window.Telegram.WebApp.initData;

window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;

window.document.addEventListener('touchstart', () => false, false);

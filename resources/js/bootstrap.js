import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Attach CSRF token header if meta tag present (needed for Backpack datatables POST /contact/search)
const tokenMeta = document.head.querySelector('meta[name="csrf-token"]');
if (tokenMeta?.content) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.content;
}

// Ensure jQuery AJAX (used by Backpack DataTables) gets CSRF token
if (window.jQuery && tokenMeta?.content) {
	window.jQuery.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': tokenMeta.content }
	});
}

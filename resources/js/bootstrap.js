import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Add request/response interceptors for better error handling
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            // Server responded with error status
            const status = error.response.status;

            if (status === 401) {
                window.location.href = '/login';
            } else if (status === 403) {
                alert('You do not have permission to perform this action.');
            } else if (status === 419) {
                alert('Your session has expired. Please refresh the page.');
                window.location.reload();
            } else if (status >= 500) {
                alert('A server error occurred. Please try again later.');
            }
        }

        return Promise.reject(error);
    }
);

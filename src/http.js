import axios from 'axios';
import store from './store'; // Import the store

const apiClient = axios.create({
  baseURL: 'http://localhost/api' // XAMPP PHP backend under htdocs/api
});

// Request Interceptor: Add Authorization header
apiClient.interceptors.request.use(config => {
  const token = store.state.auth.token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  // Let Axios set the Content-Type header
  if (config.data instanceof FormData) {
    // When sending FormData, the browser will set the Content-Type to 'multipart/form-data'
    // and include the boundary. We need to delete any existing Content-Type header
    // for this to work correctly.
    delete config.headers['Content-Type'];
  } else if (!config.headers['Content-Type']) {
    // For other requests, if no content type is set, default to JSON
    config.headers['Content-Type'] = 'application/json';
  }

  return config;
}, error => {
  return Promise.reject(error);
});

// Response Interceptor: Handle 401 Unauthorized errors
apiClient.interceptors.response.use(response => {
  // If the request was successful, just return the response
  return response;
}, error => {
  // Check if the error is a 401 Unauthorized
  if (error.response && error.response.status === 401) {
    // Dispatch the logout action
    // We use store.dispatch directly here to avoid circular dependencies
    // if this module were to be imported inside the store.
    store.dispatch('auth/logout');
    
    // Redirect to login page. 
    // Note: This might not be the best place for redirection, 
    // router is a better place, but for simplicity, we can do it here.
    // A better approach is to listen for a state change in App.vue and redirect.
    if (window.location.pathname !== '/login') {
      window.location.replace('/login');
    }
  }
  
  // Return the error to the calling function
  return Promise.reject(error);
});

export default apiClient;
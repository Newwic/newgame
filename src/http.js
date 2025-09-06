import axios from 'axios';
import store from './store'; // Import the store

const apiClient = axios.create({
  baseURL: 'http://localhost/api', // Your backend URL
  headers: {
    'Content-Type': 'application/json'
  }
});

// Request Interceptor: Add Authorization header
apiClient.interceptors.request.use(config => {
  const token = store.state.auth.token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
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
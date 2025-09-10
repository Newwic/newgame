import apiClient from '../../http';

// Load state from localStorage
const getInitialState = () => {
  try {
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user'));
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    if (token && user && isLoggedIn) {
      return {
        token: token,
        user: user,
        isLoggedIn: true,
      };
    }
  } catch (e) {
    // If localStorage is corrupted, return default state
    console.error('Could not parse user from localStorage', e);
  }
  return {
    token: null,
    user: null,
    isLoggedIn: false,
  };
};

const state = getInitialState();

const getters = {
  isAuthenticated: state => state.isLoggedIn,
  currentUser: state => state.user,
};

const mutations = {
  SET_AUTH_DATA(state, { token, user }) {
    state.token = token;
    state.user = user;
    state.isLoggedIn = true;

    // Save to localStorage
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
    localStorage.setItem('isLoggedIn', 'true');
  },
  CLEAR_AUTH_DATA(state) {
    state.token = null;
    state.user = null;
    state.isLoggedIn = false;

    // Clear from localStorage
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('isAdminLoggedIn'); // Also clear admin flag if it exists
  },
  SET_USER(state, user) {
    state.user = user;
    // Also update the user in localStorage
    localStorage.setItem('user', JSON.stringify(user));
  },
};

const actions = {
  async login({ commit }, credentials) {
    // We return the promise to the component to handle success/failure
    return new Promise((resolve, reject) => {
  apiClient.post('/login.php', credentials)
        .then(response => {
          const { token, user } = response.data;
          commit('SET_AUTH_DATA', { token, user });
          resolve(response);
        })
        .catch(error => {
          // On failure, we clear any existing auth data
          commit('CLEAR_AUTH_DATA');
          reject(error);
        });
    });
  },

  logout({ commit }) {
    commit('CLEAR_AUTH_DATA');
  },

  updateProfile({ commit }, userData) {
    return new Promise((resolve, reject) => {
      apiClient.post('/update_profile.php', userData)
        .then(response => {
          const { user } = response.data;
          commit('SET_USER', user);
          resolve(response);
        })
        .catch(error => {
          reject(error);
        });
    });
  },

  loginAdmin({ commit }) {
    // This is a mock login for the hardcoded admin
    // In a real app, this would hit a secure admin login endpoint
    const adminUser = { id: 'admin', name: 'Admin', email: 'admin@newgame.com' };
    const fakeToken = 'admin-secret-token'; // Placeholder token
    commit('SET_AUTH_DATA', { token: fakeToken, user: adminUser });
  },
};

export default {
  namespaced: true, // Important for modular stores
  state,
  getters,
  mutations,
  actions,
};

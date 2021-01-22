const user = {
    namespaced: true,
    state: {
        access_token: window.localStorage.getItem('access_token') ?? null,
        user: window.localStorage.getItem('user') ? JSON.parse(window.localStorage.getItem('user')) : null,
    },
    mutations: {
        SET_TOKEN: (state, token) => {
            state.access_token = token
            window.localStorage.setItem('access_token', token)
        },
        SET_USER: (state, user) => {
            state.user = user
            window.localStorage.setItem('user', JSON.stringify(user))
        },
        LOG_OUT: (state) => {
            state.user = null
            state.access_token = null
            window.localStorage.removeItem('user')
            window.localStorage.removeItem('access_token')
        }
    },
    actions: {
        loginByEmail({ commit, dispatch }, data) {
            return new Promise((resolve, reject) => {
                window.axios.post('/api/auth/login', data).then(response => {
                    commit('SET_TOKEN', response.data.access_token)
                    dispatch('getUser')
                    resolve(response)
                }).catch(err => {
                    reject(err)
                })
            })
        },
        getUser({ commit }) {
            window.axios.get('/api/auth/me').then(response => {
                commit('SET_USER', response.data)
            })
        },
        logout({commit}) {
            window.axios.get('/api/auth/logout').then(response => {
                commit('LOG_OUT')
            })
        }
    }
}

export default user

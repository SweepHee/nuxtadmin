export const state = () => ({
  me: null,
  list: [],
  idDuplicate: [],
  status: [],
  create: []
})

export const mutations = {
  getUsers (state, payload) {
    state.list = payload
  },
  createUser (state, payload) {
    state.create = payload
  },
  duplicateStatus (state, payload) {
    state.idDuplicate = payload
  }
}

export const actions = {
  getUserList ({ commit }, payload) {
    this.$axios.post('/user/list', {
      page: payload.page,
      limit: payload.limit,
      auth: payload.auth
    }, {
      withCredentials: true
    })
      .then((res) => {
        commit('getUsers', res.data)
      })
      .catch((err) => {
        console.error(err)
      })
  },
  signUp ({ commit }, payload) {
    this.$axios.post('/user/create', {
      id: payload.data.id,
      email: payload.data.email1 + payload.data.email2,
      name: payload.data.name,
      password: payload.data.password
    }, {
      withCredentials: true
    })
      .then((res) => {
        commit("createUser", res.data)
      })
      .catch((err) => {
        console.error(err)
      })
  },
  isUser ({ commit }, payload) {
    commit("duplicateStatus", [])
    this.$axios.post('/user/check', {
      type: payload.type,
      data: payload.data
    })
      .then((res) => {
        commit("duplicateStatus", res.data)
      })
      .catch((err) => {
        console.error(err)
        alert(`에러가 발생했습니다${err}`)
      })
  }
}

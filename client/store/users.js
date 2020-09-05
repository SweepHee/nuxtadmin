export const state = () => ({
  me: null,
  list: []
})

export const mutations = {
  getUsers (state, payload) {
    state.list = payload
  },
  createdUser (state, payload) {
    state.me = payload
  }
}

export const actions = {
  getUserList ({ commit }, payload) {
    this.$axios.post('/user/view', {
      email: payload.email
    }, {
      withCredentials: false
    })
      .then((res) => {
        commit('getUsers', res.data)
      })
      .catch((err) => {
        console.error(err)
      })
  },
  signUp ({ commit }, payload) {
    const form = payload.data
    const fail = {}
    if (form.id === '') {
      fail.msg = '아이디를 입력해주세요.'
    }

    if (form.password === '') {
      fail.msg = '비밀번호를 입력해주세요.'
    }

    if (form.passwordCheck === '') {
      fail.msg = '비밀번호 확인을 입력해주세요.'
    }

    if (form.email1 === '') {
      fail.msg = '이메일을 입력해주세요.'
    }

    if (form.email2 === '') {
      fail.msg = '이메일을 정확히 입력해주세요.'
    }

    this.$axios.post('/user/create', {
      email: payload.data.email,
      name: payload.data.name,
      password: payload.data.password
    }, {
      withCredentials: true
    })
      .then((res) => {
        console.log(res)
        // commit("createdUser", res.data);
      })
      .catch((err) => {
        console.error(err)
      })
  }
}

import Vue from 'vue'
import Vuex from 'vuex'
import sourceData from '@/data'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    ...sourceData,
    authId: '4' // Batman
  },
  actions: {
    openMessage ({ commit }, message) {
      const messageId = message['.key']
      commit('appendMessageToOpenMessages', { message, messageId })
    },
    closeMessage ({ commit }, message) {
      const messageId = message['.key']
      commit('removeMessageFromOpenMessages', { message, messageId })
    },
    takeOwnership ({ commit }, { message, userId }) {

    },
    createMessage ({ commit, state }, message) {
      const messageId = 'greatMessage' + Math.random()
      message['.key'] = messageId
      message.userId = state.authId
      message.createdAt = Math.floor(Date.now() / 1000)

      commit('setMessage', { message, messageId })
      commit('appendMessageToUserSent', { userId: message.userId, messageId })
      return Promise.resolve(state.messages[messageId])
    }

  },
  mutations: {
    setMessage (state, { message, messageId }) {
      Vue.set(state.messages, messageId, message)
    },

    appendMessageToUserSent (state, { messageId, userId }) {
      const user = state.users[userId]
      if (!user.sent) {
        Vue.set(user, 'sent', {})
      }
      Vue.set(user.sent, messageId, messageId)
    },

    appendMessageToOpenMessages (state, { message, messageId }) {
      Vue.set(state.userState.openTabs, messageId, messageId)
    },
    removeMessageFromOpenMessages (state, { message, messageId }) {
      Vue.delete(state.userState.openTabs, messageId, messageId)
    }
  },
  modules: {
  }
})

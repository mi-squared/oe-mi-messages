import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import sourceData from '@/data'

Vue.use(Vuex)

const baseUrl = ''

export default new Vuex.Store({
  state: {
    ...sourceData,
    // users: {},
    userState: {},
    messageFilters: {},
    messageTools: {},
    messages: {},
    replies: {},
    attachments: {},
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
    },

    fetchMessage ({ state, commit }, { id }) {
      return new Promise((resolve, reject) => {
        axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_message', {
          id: id
        }).then(function (response) {
          const message = response.data
          commit('setMessage', { message: { ...message, '.key': message.id }, messageId: message.id })
          resolve(state.messages[message.id])
        })
      })
    },

    fetchAllMessageFilters ({ state, commit }) {
      window.addEventListener('unhandledrejection', function (promiseRejectionEvent) {
        // handle error here, for example log
        console.log(promiseRejectionEvent)
      })
      // This API call fetches all the message filters, and pre-fetches messages
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_all_message_filters')
        .then(response => {
          const messageFiltersAll = Object.values(response.data)
          // First commit all the messages to the state, then commit the message filters
          messageFiltersAll.forEach(messageFilter => {
            // Create a messages array where the key and value are the message ID for passing to message filter
            var messages = {}
            messageFilter.messages.forEach(message => {
              messages[message.id] = message.id
            })
            commit('setMessageFilter', { messageFilter: { ...messageFilter, '.key': messageFilter.id, messages: messages }, messageFilterId: messageFilter.id })
            messageFilter.messages.forEach(message => {
              // commit message, TODO don't hard-code recipients
              const recipients = {
                users: {
                  2: '2',
                  3: '3'
                },
                teams: {}
              }
              const assignedTo = null
              // Create attachments array where the key and value are the attachment ID
              var attachments = {}
              message.attachments.forEach(attachment => {
                attachments[attachment.id] = attachment.id
              })
              commit('setMessage', { message: { ...message, '.key': message.id, recipients: recipients, attachments: attachments, assignedTo: assignedTo }, messageId: message.id })
              // commit attachments
              message.attachments.forEach(attachment => {
                commit('setAttachment', { attachment: { ...attachment, '.key': attachment.id }, attachmentId: attachment.id })
              })
            })
          })
          return state.messageFilters
        })
        .catch(error => {
          console.error(error)
          // return Promise.reject(error)
        })
    },

    fetchAllUsers ({ state, commit }) {
      return new Promise((resolve, reject) => {
        // This API call fetches all the users
        axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_all_users')
          .then(function (response) {
            const allUsers = Object.values(response.data)
            allUsers.forEach(user => {
              commit('setUser', {
                user: {
                  ...user,
                  '.key': user.id
                },
                userId: user.id
              })
            })
            resolve(state.users)
          })
      })
    }
  },
  mutations: {
    setMessage (state, { message, messageId }) {
      Vue.set(state.messages, messageId, message)
    },

    setUser (state, { user, userId }) {
      Vue.set(state.users, userId, user)
    },

    setAttachment (state, { attachment, attachmentId }) {
      Vue.set(state.attachments, attachmentId, attachment)
    },

    setMessageFilter (state, { messageFilter, messageFilterId }) {
      Vue.set(state.messageFilters, messageFilterId, messageFilter)
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

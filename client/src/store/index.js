import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
// import wsPlugin from './wsPlugin.js'
import sourceData from '@/data'

Vue.use(Vuex)

const baseUrl = ''

export default new Vuex.Store({
  state: {
    ...sourceData,
    // users: {},
    userState: {
      openTabs: {},
      activeFilter: '1'
    },
    messageOptions: {
      statusOptions: {
        new: {
          '.key': 'new',
          color: '#3e5e8e',
          iconClass: 'far fa-check-circle',
          title: 'New'
        },
        'in-progress': {
          '.key': 'in-progress',
          color: '#c8bc4d',
          iconClass: 'fas fa-check-circle',
          title: 'In-Progress'
        },
        complete: {
          '.key': 'complete',
          color: '#17d923',
          iconClass: 'fas fa-check-circle',
          title: 'Complete'
        }
      }
    },
    messageMeta: {},
    jobs: {},
    attachments: {}, // Links to files, can have multiple files representing one attachment (versions, like PDF, docx, etc)
    files: {}, // Each version of an attachment, physical file
    messageFilters: {},
    messageTools: {},
    messages: {},
    replies: {},
    authId: '4' // Batman
  },
  actions: {
    openMessage ({ commit, state }, message) {
      const messageId = message['.key']
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!open_message', {
        params: {
          userId: state.authId,
          messageId: messageId
        }
      }).then(response => {
        const messageMeta = response.data
        commit('setMessageMeta', { messageMeta, messageId })
      })
    },
    closeMessage ({ commit, state }, message) {
      const messageId = message['.key']
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!close_message', {
        params: {
          userId: state.authId,
          messageId: messageId
        }
      }).then(response => {
        const messageMeta = response.data
        commit('setMessageMeta', { messageMeta, messageId })
      })
    },
    takeOwnership ({ commit }, { message, userId }) {},
    moveToList ({ commit }, { message, fromListId, toListId, userId }) {
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!move_message', {
        params: {
          fromFilterId: fromListId,
          toFilterId: toListId,
          userId: userId,
          messageId: message['.key']
        }
      }).then(response => {
        // Remove the message from the old message filter
        commit('removeMessageFromFilter', { messageId: message['.key'], filterId: fromListId })
        // Append message to new Message Filter
        commit('appendMessageToFilter', { messageId: message['.key'], filterId: toListId })
        return response.data
      })
    },
    setMessageMeta ({ commit }, { message, userId, meta }) {
      return axios.post(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php', {
        action: 'message!set_message_meta',
        meta: meta,
        userId: userId,
        messageId: message['.key']
      }).then(response => {
        const messageMeta = response.data
        commit('setMessageMeta', { messageMeta: { ...messageMeta, '.key': messageMeta.messageId }, messageId: messageMeta.messageId })
        return response.data
      })
    },
    setMessageStatus ({ commit, state }, { message, status }) {
      message.status = status['.key']
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!set_message_status', {
        params: {
          status: message.status,
          messageId: message['.key']
        }
      }).then(response => {
        commit('setMessage', { message, messageId: message['.key'] })
        return response.data
      })
    },

    addReply ({ commit, state }, { message, text, userId }) {
      return axios({
        url: baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php',
        method: 'post',
        data: {
          action: 'message!add_reply_to_message',
          body: text,
          messageId: message['.key'],
          userId: userId
        }
      }).then(response => {
        const reply = { ...response.data, '.key': response.data.id }
        commit('setReply', { reply, replyId: reply['.key'] })
        commit('appendReplyToMessage', { messageId: message['.key'], replyId: reply['.key'] })
        return reply
      })
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

    fetchPreview ({ state, commit }, { attachment }) {
      // Given an attachment DOCX, ask the server for a PDF version
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=file!fetch_pdf', {
        params: {
          attachmentId: attachment['.key']
        }
      }).then(response => {
        const file = response.data
        commit('setFile', { file: { ...file, '.key': file.id }, fileId: file.id })
        return state.files[file.id]
      })
    },

    fetchHtml ({ state, commit }, { attachment }) {
      // Given an attachment DOCX, ask the server for a HTML version
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=file!fetch_html', {
        params: {
          attachmentId: attachment['.key']
        }
      }).then(response => {
        const file = response.data
        commit('setFile', { file: { ...file, '.key': file.id }, fileId: file.id })
        return state.files[file.id]
      })
    },

    pushHtml ({ state, commit }, { file, content }) {
      return axios({
        url: baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php',
        method: 'post',
        data: {
          action: 'file!push_html',
          fileId: file['.key'],
          content: content
        }
      }).then(response => {
        // update the new files given our attachment
        const attachment = response.data
        var files = {}
        attachment.files.forEach(file => {
          if (file.revision === attachment.revision) {
            files[file.id] = file.id
            commit('setFile', { file: { ...file, '.key': file.id }, fileId: file.id })
          }
        })
        const updatedAttachment = { ...attachment, '.key': attachment.id, files: files }
        commit('setAttachment', { attachment: updatedAttachment, attachmentId: attachment.id })
        return updatedAttachment
      })
    },

    fetchMessage ({ state, commit }, { id }) {
      return new Promise((resolve, reject) => {
        axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_message', {
          params: {
            id: id
          }
        }).then(function (response) {
          const message = response.data
          // Create attachments array where the key and value are the attachment ID
          var attachments = {}
          message.attachments.forEach(attachment => {
            attachments[attachment.id] = attachment.id
          })
          // commit message, TODO recipients and teams no longer used on the message object
          const recipients = {}
          const assignedTo = null
          commit('setMessage', { message: { ...message, '.key': message.id, attachments: attachments, recipients: recipients, assignedTo: assignedTo }, messageId: message.id })
          resolve(state.messages[message.id])
        })
      })
    },

    fetchAllTeams ({ state, commit }, { userId }) {
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_all_teams', {
        params: {
          userId: userId
        }
      }).then(response => {
        const teams = Object.values(response.data)
        teams.forEach(team => {
          commit('setTeam', { team: { ...team, '.key': team.id }, teamId: team.id })
        })
        return teams
      })
    },

    /*
    Fetch all message meta (state of a message for a user) for a particular user.
    Add the meta to the messageMeta store, indexed (and keyed) by the messageId NOT the messageMetaId
    to enable faster lookup. The messageMeta.id field will still contain the messageMeta id
     */
    fetchAllMessageMetaForUser ({ state, commit }, { userId }) {
      return new Promise((resolve, reject) => {
        axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_all_message_meta_for_user', {
          params: {
            userId: userId
          }
        }).then(function (response) {
          const allMessageMeta = response.data
          allMessageMeta.forEach(messageMeta => {
            commit('setMessageMeta', { messageMeta: { ...messageMeta, '.key': messageMeta.messageId }, messageId: messageMeta.messageId })
          })
          resolve(state.messageMeta)
        })
      })
    },

    fetchAllMessageFilters ({ state, commit }, { userId }) {
      window.addEventListener('unhandledrejection', function (promiseRejectionEvent) {
        // handle error here, for example log
        console.log(promiseRejectionEvent)
      })
      // This API call fetches all the message filters, and pre-fetches messages
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_all_message_filters', {
        params: {
          userId: userId
        }
      }).then(response => {
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
            const teams = {}
            message.teams.forEach(team => {
              teams[team.id] = team.id
            })

            const recipients = message.recipients
            message.recipients.forEach(recipient => {
              recipients[recipient.id] = recipient.id
            })

            const assignedTo = null
            // Create attachments array where the key and value are the attachment ID
            var attachments = {}
            message.attachments.forEach(attachment => {
              var files = {}
              attachment.files.forEach(file => {
                files[file.id] = file.id
                commit('setFile', { file: { ...file, '.key': file.id }, fileId: file.id })
              })
              attachments[attachment.id] = attachment.id
              commit('setAttachment', { attachment: { ...attachment, '.key': attachment.id, files: files }, attachmentId: attachment.id })
            })

            var replies = {}
            message.replies.forEach(reply => {
              replies[reply.id] = reply.id
              commit('setReply', { reply: { ...reply, '.key': reply.id }, replyId: reply.id })
            })

            commit('setMessage', { message: { ...message, '.key': message.id, teams: teams, recipients: recipients, attachments: attachments, replies: replies, assignedTo: assignedTo }, messageId: message.id })
          })
        })
        return state.messageFilters
      }).catch(error => {
        console.error(error)
        // return Promise.reject(error)
      })
    },

    fetchMessagesForFilter ({ state, commit }, { filterId, userId }) {
      return axios.get(baseUrl + '/interface/modules/custom_modules/oe-mi-messages/index.php?action=message!fetch_messages', {
        params: {
          userId: userId,
          filterId: filterId
        }
      }).then(response => {
        const messages = Object.values(response.data)
        messages.forEach(message => {
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
    setJob (state, { job, jobId }) {
      Vue.set(state.jobs, jobId, job)
    },

    setFile (state, { file, fileId }) {
      Vue.set(state.files, fileId, file)
    },

    setMessage (state, { message, messageId }) {
      Vue.set(state.messages, messageId, message)
    },

    setMessageMeta (state, { messageMeta, messageId }) {
      Vue.set(state.messageMeta, messageId, messageMeta)
    },

    setReply (state, { reply, replyId }) {
      Vue.set(state.replies, replyId, reply)
    },

    setTeam (state, { team, teamId }) {
      Vue.set(state.teams, teamId, team)
    },

    appendReplyToMessage (state, { messageId, replyId }) {
      const message = state.messages[messageId]
      if (!message.replies) {
        Vue.set(message, 'replies', {})
      }
      Vue.set(message.replies, replyId, replyId)
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

    appendMessageToFilter (state, { messageId, filterId }) {
      Vue.set(state.messageFilters[filterId].messages, messageId, messageId)
    },

    removeMessageFromFilter  (state, { messageId, filterId }) {
      Vue.delete(state.messageFilters[filterId].messages, messageId)
    },

    appendMessageToOpenMessages (state, { message, messageId }) {
      Vue.set(state.messageMeta[messageId], messageId, messageId)
    },
    removeMessageFromOpenMessages (state, { message, messageId }) {
      Vue.delete(state.userState.openTabs, messageId, messageId)
    }
  },
  modules: {
  }// ,
  // plugins: [wsPlugin()]
})

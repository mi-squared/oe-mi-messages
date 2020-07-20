<template>
  <div class="message-view-container">
    <nav class="navbar navbar-expand-md message-bar">
      <div class="back-button" @click="back()"><span class="fa fa-arrow-left"></span></div>
      <h1>{{message.subject}}</h1>
      <AppTeam v-for="team in teamRecipients" :team="team" :key="team['.key']"/>
      <div class="message-actions-container">
        <ul class="message-actions">
          <li>
            <a @click="doReply" class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="#">
              <i class="fa fa-reply"></i>
              Reply
            </a>
          </li>
          <li><StatusPopper :status="messageStatus" @change-message-status="changeMessageStatus"></StatusPopper></li>
        </ul>
      </div>
    </nav>
    <div v-if="assignable">
      Assignee
      <span v-if="isAssigned"><a href="#" ><img class="avatar" :src="assignedUser.avatar"></a></span>
      <span v-else-if="assignable"><a href="#"><span class="avatar empty">&nbsp;</span></a></span>
    </div>
    <div class="message-view" id="message-view">
      <div class="reply-item">
        <div class="media g-mb-30 media-comment">
          <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15"
               :src="from.avatar" :alt="initials">
          <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
            <div class="g-mb-15">
              <h5 class="h5 g-color-gray-dark-v1 mb-0">{{ from.name }}</h5>
              <span class="g-color-gray-dark-v4 g-font-size-12"><AppDate :timestamp="message.createdAt"/> </span>
            </div>

            <p v-html="message.body"></p>

            <div class="attachments">
              <AttachmentItem
                v-for="attachment in attachments"
                :attachment="attachment"
                :key="attachment['.key']"
              />
            </div>
          </div>
        </div>
      </div>
      <ReplyList :message="message"/>
      <div v-if="isReplying" class="message-editor">
        <TextEditor @updateContent="replyText = $event"/>
        <div class="message-editor-actions-container">
          <ul class="message-editor-actions reply">
            <li>
              <a class="submit-reply" @click="submitReply()">Send</a>
            </li>
            <li>
              <a class="submit-reply" @click="cancelReply()">Cancel</a>
            </li>
          </ul>
        </div>
      </div>
      <div v-else class="message-editor">
        <div class="message-editor-actions-container">
          <ul class="message-editor-actions">
            <li>
              <a v-if="!isReplying" @click="doReply" class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="#">
                <i class="fa fa-reply"></i>
                Reply
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AppDate from '../../AppDate'
import TextEditor from '../../TextEditor'
import AppTeam from '../../AppTeam'
import ReplyList from '../ReplyList'
import AttachmentItem from '../AttachmentItem'
import StatusPopper from '../StatusPopper'

export default {
  name: 'PageViewMessage',
  components: {
    StatusPopper,
    AttachmentItem,
    ReplyList,
    TextEditor,
    AppDate,
    AppTeam
  },
  props: {
    messageId: {
      required: true,
      type: String
    }
  },
  data () {
    return {
      isReplying: false,
      replyText: ''
    }
  },
  computed: {
    messageStatus () {
      return this.$store.state.messageOptions.statusOptions[this.message.status]
    },
    attachments () {
      if (this.message.attachments) {
        const attachmentKeys = Object.keys(this.message.attachments)
        const attachmentObjects = attachmentKeys.map(attachmentId => this.$store.state.attachments[attachmentId])
        return attachmentObjects
      } else {
        return []
      }
    },
    message () {
      return this.$store.state.messages[this.messageId]
    },
    from () {
      return this.$store.state.users[this.message.userId]
    },
    isAssigned () {
      if (this.message.assignedTo === null) {
        return false
      } else {
        return true
      }
    },
    initials () {
      return this.from.name.match(/\b(\w)/g).join('').toUpperCase()
    },
    assignedUser () {
      const assignedToUser = this.$store.state.users[this.message.assignedTo]
      return assignedToUser
    },
    assignable () {
      if (this.message.assignable === true) {
        return true
      } else {
        return false
      }
    },
    meta () {
      return this.$store.state.messageMeta[this.messageId]
    },
    recipientsList () {
      return Object.keys(this.message.recipients.users).map(userId => { return this.$store.state.users[userId].name }).join(', ')
    },
    teamRecipients () {
      // Safety-check in case there are no teams
      if (this.message.recipients.teams) {
        return Object.keys(this.message.recipients.teams).map(teamId => {
          return this.$store.state.teams[teamId]
        })
      } else {
        return []
      }
    }
  },
  methods: {
    doReply () {
      this.isReplying = true
      const el = document.getElementById('message-view')
      el.scrollTop = el.scrollHeight
    },
    markAsRead () {
      this.meta.isRead = 1
      this.$store.dispatch('setMessageMeta', { message: this.message, meta: this.meta, userId: this.$store.state.authId })
    },
    back () {
      this.$router.back()
    },
    changeMessageStatus (status) {
      this.$store.dispatch('setMessageStatus', { message: this.message, status: status })
    },
    submitReply () {
      this.$store.dispatch('addReply', { message: this.message, text: this.replyText, userId: this.$store.state.authId })
      this.isReplying = false
    },
    cancelReply () {
      this.isReplying = false
    }
  },
  mounted () {
    this.markAsRead()
  },
  created () {
    this.$store.dispatch('fetchMessage', { messageId: this.messageId })
      .then(message => {
        console.log(message.subject)
      })
  }
}
</script>

<style scoped lang="scss">

  .back-button {
    padding: 8px;
    cursor: pointer;
  }

  .submit-reply {
    cursor: pointer;
  }

  .message-bar {
    border-bottom: 1px solid gray;
    line-height: 1.8;
    padding: 0;
  }

  .message-editor {
    padding: 1.5rem;
    margin-bottom: 80px;
    margin-left: 44px;
    min-height: 340px;
  }

  .message-view-container {
    height: 100%;
    padding-top: 44px;
    padding-left: 8px;
  }

  .message-view {
    height: 100%;
    margin: 0;
    overflow-y: auto;
  }

  .message-editor-actions {
    width: 100%;
    margin-right: 0;
    margin-left: 0;
    border-width: .2rem;
  }

  .message-actions-container {
    float: right;
  }

  ul.message-actions {
    list-style-type: none;
    display: block;
    float: right;
    margin-bottom: 0px;
  }
  ul.message-actions li {
    display:inline-block;
    float: right;
    padding: 8px;
  }

  ul.message-editor-actions {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    &.reply {
      border-left: 1px solid gray;
      border-right: 1px solid gray;
      border-bottom: 1px solid gray;
    }
    li {
      float: left;
      display: inline-block;
      &.reply {
        border-right: 1px solid gray;
      }
    }
  }

  ul.message-editor-actions li a {
    display: block;
    text-align: center;
    padding: 4px 12px;
    text-decoration: none;
  }

  @media (min-width: 0) {
    .g-mr-15 {
      margin-right: 1.07143rem !important;
    }
  }

  @media (min-width: 0) {
    .g-mt-3 {
      margin-top: 0.21429rem !important;
    }
  }

  .g-height-50 {
    height: 50px;
  }

  .g-width-50 {
    width: 50px !important;
  }

  @media (min-width: 0) {
    .g-pa-30 {
      padding: 2.14286rem !important;
    }
  }

  .g-bg-secondary {
    background-color: rgba(0, 0, 0, 0.01) !important;
  }

  .u-shadow-v18 {
    box-shadow: 0 5px 10px -6px rgba(0, 0, 0, 0.15);
  }

  .g-color-gray-dark-v4 {
    color: #777 !important;
  }

  .g-font-size-12 {
    font-size: 0.85714rem !important;
  }

  .media-comment {
    margin-top: 20px;
  }

  .media-body {
    margin-right: 50px;
  }
</style>

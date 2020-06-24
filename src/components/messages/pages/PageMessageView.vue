<template>
  <div class="message-view-container">
    <nav class="navbar navbar-expand-md message-bar">
      <div v-if="assignable"><a href="#" ><span class="fa fa-square"></span></a></div>
      <div v-else><a href="#" ><span class="fa fa-envelope"></span></a></div>
      <h1>{{message.subject}}</h1>
      <AppTeam v-for="team in teamRecipients" :team="team" :key="team['.key']"/>
      <ul>
        <li><span class="fa fa-star"></span><span>Follow</span></li>
        <li><span class="fa fa-keyboard"></span><span>Compose</span></li>
        <li><span class="fa fa-chevron-down"></span><span>details</span></li>
      </ul>
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
               :src="from.avatar" :alt="from.name">
          <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
            <div class="g-mb-15">
              <h5 class="h5 g-color-gray-dark-v1 mb-0">{{ from.name }}</h5>
              <span class="g-color-gray-dark-v4 g-font-size-12"><AppDate :timestamp="message.createdAt"/> </span>
            </div>

            <p v-html="message.body"></p>

            <ul class="list-inline d-sm-flex my-0">
              <li class="list-inline-item g-mr-20">
                <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="#!">
                  <i class="fa fa-thumbs-up g-pos-rel g-top-1 g-mr-3"></i>
                  178
                </a>
              </li>
              <li class="list-inline-item g-mr-20">
                <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="#!">
                  <i class="fa fa-thumbs-down g-pos-rel g-top-1 g-mr-3"></i>
                  34
                </a>
              </li>
              <li class="list-inline-item ml-auto">
                <a class="u-link-v5 g-color-gray-dark-v4 g-color-primary--hover" href="#!">
                  <i class="fa fa-reply g-pos-rel g-top-1 g-mr-3"></i>
                  Reply
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <ReplyList :message="message"/>
      <div id="scroll-to-me"></div>
      <div class="message-editor">
        <TextEditor/>
        <div class="message-editor-actions-container">
          <ul class="message-editor-actions">
            <li>
              <a class="">Send</a>
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

export default {
  name: 'PageViewMessage',
  components: {
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
  computed: {
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
  mounted () {
    const el = document.getElementById('message-view')
    el.scrollTop = el.scrollHeight
  }
}
</script>

<style scoped>

  .message-bar {
    border-bottom: 1px solid gray;
    line-height: 1.8;
    padding: 0;
  }

  .message-editor {
    padding: 1.5rem;
    margin-bottom: 80px;
  }

  .message-view-container {
    height: 100%;
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

  ul.message-editor-actions {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    border-bottom: 1px solid gray;
  }

  li {
    float: left;
    display: inline-block;
    border-right: 1px solid gray;
  }

  li a {
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
    background-color: #fafafa !important;
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
    margin-top: 20px
  }
</style>

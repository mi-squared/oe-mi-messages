<template>
  <tr @click="viewMessage()" class="message-list-item" :class="{assignable: assignable}">
    <td><input type="checkbox"></td>
    <td class="toolbar-column">
      <ul class="toolbar">
        <li><span class="status" :class="statusClass"></span></li>
      </ul>
    </td>
    <td>
      <div class="subject">
        <span :class="{unread : unread}">{{message.subject}}</span>
        <TagItem
          v-for="tag in tags"
          :tag="tag"
          :key="tag['.key']"
        />
      </div>
      <div clas="preview">{{message.body}}</div>
      <div class="attachments">
        <AttachmentItem
          v-for="attachment in attachments"
          :attachment="attachment"
          :key="attachment['.key']"
        />
      </div>
    </td>
    <td class="toolbar-column">
      <ul class="toolbar">
        <li><span class="far fa-comment-alt"></span> {{ replyCount }}</li>
        <li v-if="hasViewers"><span class="fa fa-eye" :title="viewersList"></span></li>
        <li v-else><span><!-- Display Nothing if no viewers --></span></li>
      </ul>
    </td>
    <td>
      <div>
        <span :class="{unread : unread}">{{fromName}}</span>
      </div>
      <div class="actors">
        <AppTeam v-for="team in teamRecipients" :team="team" :key="team['.key']"/>&nbsp;
        <span class="recipient-list">{{recipientsList}}</span>
      </div>
    </td>
    <td><span :class="{unread : unread}"><AppDate :timestamp="this.message.createdAt"/></span></td>
    <td class="toolbar-column">
      <ul class="toolbar">
        <li v-if="isAssigned"><a href="#" ><img class="avatar" :src="assignedUser.avatar"></a></li>
        <li v-else><a href="#"><span class="avatar empty">&nbsp;</span></a></li>
<!--        <li v-if="assignable && !isAssigned"><a @click="takeOwnership" ><span class="fa fa-hand-paper" :class="{assignable: assignable}"></span></a></li>-->
<!--        <li v-else><a href=""><span class="fa fa-hand-paper"></span></a></li>-->
        <li><a href="#"><span class="fa fa-archive"></span></a></li>
      </ul>
    </td>
  </tr>
</template>

<script>
import TagItem from './TagItem'
import AttachmentItem from './AttachmentItem'
import AppDate from '../AppDate'
import AppTeam from '../AppTeam'
export default {
  name: 'MessageListItem',
  components: { AppTeam, AppDate, AttachmentItem, TagItem },
  props: {
    message: {
      required: true,
      type: Object
    }
  },
  methods: {
    viewMessage () {
      this.$router.push({
        name: 'PageMessageView',
        params: {
          messageId: this.message['.key']
        }
      })

      this.$store.dispatch('openMessage', this.message)
    },
    takeOwnership () {
      this.$store.dispatch('takeOwnership', { message: this.message, userId: 1 })
    }
  },
  computed: {
    unread  () {
      if (this.message.isRead === false) {
        return true
      } else {
        return false
      }
    },
    replyCount () {
      let replyCount = 0
      if (this.message.replies) {
        replyCount = Object.keys(this.message.replies).length
      }
      return replyCount
    },
    isAssigned () {
      if (this.message.assignedTo === null) {
        return false
      } else {
        return true
      }
    },
    statusClass () {
      let statusClass = ''
      if (this.message.isRead === false) {
        statusClass = 'far fa-check-circle unread'
      } else if (this.message.isRead === true &&
        this.message.status === 'complete') {
        statusClass = 'fas fa-check-circle complete'
      } else {
        statusClass = 'far fa-check-circle new'
      }
      return statusClass
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
    attachments () {
      if (this.message.attachments) {
        const attachmentKeys = Object.keys(this.message.attachments)
        const attachmentObjects = attachmentKeys.map(attachmentId => this.$store.state.attachments[attachmentId])
        return attachmentObjects
      } else {
        return []
      }
    },
    tags () {
      if (this.message.tags) {
        const tagObjects = Object.keys(this.message.tags).map(tagId => this.$store.state.tags[tagId])
        return tagObjects
      } else {
        return []
      }
    },
    fromName () {
      return this.$store.state.users[this.message.userId].name
    },
    viewersList () {
      let viewers = []
      if (this.message.viewers) {
        viewers = Object.keys(this.message.viewers)
      }
      let verb = ''
      if (viewers.length > 1) {
        verb = ' are viewing'
      } else {
        verb = ' is viewing'
      }
      const vlist = viewers.map(userId => { return this.$store.state.users[userId].name }).join(', ')
      return vlist + verb
    },
    hasViewers () {
      let viewers = []
      if (this.message.viewers) {
        viewers = Object.keys(this.message.viewers)
      }
      if (viewers.length > 0) {
        return true
      } else {
        return false
      }
    },
    recipientsList () {
      return Object.keys(this.message.recipients.users).map(userId => { return this.$store.state.users[userId].name }).join(', ')
    },
    teamRecipients () {
      return Object.keys(this.message.recipients.teams).map(teamId => { return this.$store.state.teams[teamId] })
    }
  }
}
</script>

<style scoped lang="scss">

  .toolbar .fa-hand-paper.assignable {
    color: $primary;
  }

  .toolbar .fa-hand-paper {
    color: gainsboro;
  }

  .status {

    &.unread {
      color: $primary;
    }

    &.new {
      color: rgba(200,200,200,1)
    }

    &.complete {
      color: rgba(25,218,36,0.5);
    }
  }

  .fa-circle {
    color: red;
  }

  .fa-circle.unread {
    opacity: 1;
    color: $primary;
  }

  .open {
    color: #f6993f;
  }

  .unread {
    font-weight: bold;
  }

  .actors {
    max-width: 180px;
  }

  .recipient-list {
    font-style: italic;
    font-size: smaller;
  }

  .message-list-item {
    border-left: $primary;
    border-left-style: none;
    border-left-style: solid;
  }

  tr:nth-child(even){
    background-color: rgba(230,235,240,0.2);
  }
  tr:nth-child(odd){
    background-color: rgba(255,255,255,0.5);
  }
  td {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 0.75rem;
    padding-right: 0.25rem;
  }

  .message-list-item.assignable {
    border-left: red;
    border-left-style: none;
    border-left-style: solid;
  }

  ul.toolbar {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }

  ul.toolbar li {
    float: left;
    display: inline-block;
    width: 48px;
  }

  ul.toolbar li a {
    display: block;
    text-align: center;
    padding: 4px 4px;
    text-decoration: none;
  }

</style>

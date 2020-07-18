<template>
  <tr class="message-list-item" :class="{assignable: assignable}">
    <td><input type="checkbox" :id="checkboxId" :value="messageId" @change="onCheck" v-model="iAmSelected"></td>
    <td class="toolbar-column">
      <ul class="toolbar">
        <li>
          <StatusPopper
            @change-message-status="changeMessageStatus"
            :status="messageStatus"
          />
        </li>
      </ul>
    </td>
    <td class="view-details" @click="viewMessage()">
      <div class="subject">
        <span :class="{unread : unread}">{{message.subject}}</span>
        <TagItem
          v-for="tag in tags"
          :tag="tag"
          :key="tag['.key']"
        />
      </div>
      <div class="preview">{{ messagePreview }}</div>
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
    <td class="actions-column">
      <ul class="toolbar">
        <li v-if="isAssigned"><a href="#" ><img class="avatar" :src="assignedUser.avatar"></a></li>
        <li v-else><a href="#"><span class="avatar empty">&nbsp;</span></a></li>
<!--        <li v-if="assignable && !isAssigned"><a @click="takeOwnership" ><span class="fa fa-hand-paper" :class="{assignable: assignable}"></span></a></li>-->
<!--        <li v-else><a href=""><span class="fa fa-hand-paper"></span></a></li>-->
        <li>
          <Popper ref="popper" trigger="hover" :stopPropagation="true" :options="{ placement: 'left' }">
            <div class="popper">
              <a v-if="listId == 1" class="popper-item" @click="moveToArchive()"><span class="fa fa-archive"></span> Archive</a>
              <a v-else class="popper-item" @click="moveToInbox()"><span class="fa fa-inbox"></span> Inbox</a>
              <a v-if="unread" class="popper-item" @click="markAsRead()"><span class="fa fa-eye-slash"></span> Mark as Read</a>
              <a v-else class="popper-item" @click="markAsUnread()"><span class="fa fa-eye"></span> Mark as Unread</a>
            </div>

            <a href="#" slot="reference"><span class="message-item-more-actions fa fa-ellipsis-v"></span></a>
          </Popper>

        </li>
      </ul>
    </td>
  </tr>
</template>

<script>
import TagItem from './TagItem'
import AttachmentItem from './AttachmentItem'
import AppDate from '../AppDate'
import AppTeam from '../AppTeam'
import StatusPopper from './StatusPopper'
import Popper from 'vue-popperjs'

export default {
  name: 'MessageListItem',
  components: { StatusPopper, Popper, AppTeam, AppDate, AttachmentItem, TagItem },
  props: {
    message: {
      required: true,
      type: Object
    },
    selected: {
      required: false,
      type: Array
    },
    listId: {
      required: true,
      type: String
    }
  },
  methods: {
    onCheck () {
      this.$emit('message-selected', this.message['.key'])
    },
    viewMessage () {
      this.$router.push({
        name: 'PageMessageView',
        params: {
          messageId: this.message['.key']
        }
      })

      this.$store.dispatch('openMessage', this.message)
    },
    moveToArchive () {
      this.$store.dispatch('moveToList', { message: this.message, fromListId: this.listId, toListId: 3, userId: this.$store.state.authId })
    },
    moveToInbox () {
      this.$store.dispatch('moveToList', { message: this.message, fromListId: this.listId, toListId: 1, userId: this.$store.state.authId })
    },
    markAsRead () {
      this.meta.isRead = 1
      this.$store.dispatch('setMessageMeta', { message: this.message, meta: this.meta, userId: this.$store.state.authId })
    },
    markAsUnread () {
      this.meta.isRead = 0
      this.$store.dispatch('setMessageMeta', { message: this.message, meta: this.meta, userId: this.$store.state.authId })
    },
    takeOwnership () {
      this.$store.dispatch('takeOwnership', { message: this.message, userId: this.$store.state.authId })
    },
    changeMessageStatus (status) {
      this.$store.dispatch('setMessageStatus', { message: this.message, status: status })
    }
  },
  computed: {
    iAmSelected: {
      get () {
        if (this.selected &&
          this.selected.includes(this.messageId)) {
          return true
        } else {
          return false
        }
      },
      set (value) {
        console.log(this.message['.key'] + ': ' + value)
      }
    },
    checkboxId () {
      return 'checkbox-' + this.message['.key']
    },
    meta () {
      return this.$store.state.messageMeta[this.messageId]
    },
    unread  () {
      if (this.meta &&
        this.meta.isRead) {
        return false
      } else {
        return true
      }
    },
    messageId () {
      return this.message['.key']
    },
    messageStatus () {
      return this.$store.state.messageOptions.statusOptions[this.message.status]
    },
    messagePreview () {
      const string = this.message.body.replace(/(<([^>]+)>)/ig, ' ')
      if (string.length > 80) {
        return string.slice(0, 80) + '...'
      } else {
        return string
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

  .actions-column {
    vertical-align: middle;
    .popper .popper-item {
      cursor: pointer;
      text-align: left;
    }
  }

  .message-item-more-actions {
    font-size: 28px;
    padding: 4px;
    color: #b4b9be;
  }

  .toolbar .fa-hand-paper.assignable {
    color: $primary;
  }

  .toolbar .fa-hand-paper {
    color: gainsboro;
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

  td.view-details {
    cursor: pointer;
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

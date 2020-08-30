<template>
  <div class="message-list">
<!--    <div class="text-uppercase text-bold">id selected: {{ selected }}</div>-->
    <div class="message-filters">
      <div>
        <Popper ref="select-all-options-popper" trigger="clickToToggle" :stopPropagation="true" :options="{ placement: 'botttom' }">
          <div class="popper">
            <a class="select-all-options-item" href="#">
              <StatusPopper :clickText="bulkStatusText"></StatusPopper>
            </a>
            <a class="select-all-options-item" href="#">
              <span class="fa fa-archive"></span> Archive
            </a>
          </div>

          <a href="#" slot="reference" class="right"><span class="btn select-all-options fa fa-caret-down"></span></a>
        </Popper>
      </div>
      <div>
        <a href="#" class="right" @click="onRefresh()"><span class="btn fa fa-sync"></span></a>
      </div>
      <div class="search-container">
        <form>
          <input type="text" placeholder="Search..." name="search" v-model="search">
          <i class="fa fa-search search-span"></i>
        </form>
      </div>
    </div>
    <div class="tableFixHead">
      <table class="table message-list-table">
        <thead>
        <tr>
          <th class="checkbox">
            <div class="select-all-checkbox">
              <input type="checkbox" v-model="selectAll" @click="select"/>
            </div>
          </th>
          <th class="status">Status</th>
          <th class="message">Message</th>
          <th class="indicators">Indicators</th>
          <th class="participants">Participants</th>
          <th class="updated-date"><!-- Updated Date --></th>
          <th class="actions"><!-- Actions --></th>
        </tr>
        </thead>
        <tbody>
        <MessageListItem
          :listId="listId"
          :selected="selected"
          @message-selected="onMessageSelected"
          v-for="message in filteredMesssages"
          :message="message"
          :key="message['.key']"
        />
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import Popper from 'vue-popperjs'
import 'vue-popperjs/dist/vue-popper.css'
import MessageListItem from './MessageListItem'
import moment from 'moment'
import StatusPopper from './StatusPopper'

export default {
  name: 'MessageList',
  components: {
    StatusPopper,
    MessageListItem,
    Popper
  },
  data () {
    return {
      page: 1,
      search: '',
      selected: [],
      selectAll: false
    }
  },
  props: {
    messages: {
      required: true,
      type: Array
    },
    listId: {
      required: true,
      type: String
    }
  },
  computed: {
    filteredMesssages () {
      let resultMessages = this.messages
      if (this.search) {
        const filteredMessages = this.messages.filter(message => {
          const props = Object.values(message)
          return props.some(prop => {
            let match = false
            if (prop) {
              if (typeof prop === 'string') {
                const lower = prop.toLowerCase()
                if (lower.includes(this.search.toLowerCase())) {
                  match = true
                }
              } else {
                if (prop.toString(10).includes(this.search)) {
                  match = true
                }
              }
            }
            return match
          })
        })
        resultMessages = filteredMessages
      }
      // Sort the filterd messages by updated date
      resultMessages.sort((message1, message2) => {
        const moment1 = moment(message1.updatedAt)
        const moment2 = moment(message2.updatedAt)
        if (moment1.isBefore(moment2)) {
          return 1
        } else if (moment2.isBefore(moment1)) {
          return -1
        }
        return 0
      })

      return resultMessages
    },
    bulkStatusText () {
      return 'Change Status [' + this.selected.length + ' messages]'
    }
  },
  methods: {
    onRefresh () {
      this.$store.dispatch('fetchAllMessageFilters', { userId: this.$store.state.authId }).then(filters => {
        console.log(filters)
      })
    },
    onMessageSelected (messageId) {
      Object.values(this.messages).forEach(message => {
        if (message['.key'] === messageId) {
          console.log('id: ' + messageId + ' "' + message.subject + '"')
        }
      })
      const index = this.selected.indexOf(messageId)
      if (index > -1) {
        this.selected.splice(index, 1)
      } else {
        this.selected.push(messageId)
      }
    },
    select () {
      console.log('select all')
      this.selected = []
      if (!this.selectAll) {
        Object.values(this.filteredMesssages).forEach(message => {
          console.log('id: ' + message['.key'] + ' "' + message.subject + '"')
          this.selected.push(message['.key'])
        })
      }
    }
  }
}
</script>

<style scoped lang="scss">

  .message-filters a.select-all-options-item {
    display: block;
    text-align: left;
    float: none;
  }

  .tableFixHead {
    overflow-y: auto;
    height: 540px;
    border-bottom: #e3e8ed;
  }
  .tableFixHead thead tr {
    border-bottom: 1px;
  }
  .tableFixHead thead th {
    position: sticky;
    top: 0;
    background-color: white;
    border-top: none;
  }

  /* Just common table stuff. Really. */
  table  {
    border-collapse: collapse;
    width: 100%;
    border-bottom: 2px;
  }
  .message-filters {
    overflow: hidden;
    background-color: #e9e9e9;
    padding: 1px;
    margin-bottom: 4px;
  }
  .message-filters a {
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 6px;
    text-decoration: none;
    font-size: 17px;
  }

  .message-filters a:hover {
    background-color: #ddd;
    color: black;
  }

  .message-filters a.active {
    background-color: #2196F3;
    color: white;
  }

  .message-filters input[type=text] {
    padding: 6px;
    font-size: 17px;
    border: none;
  }

  .message-filters .search-container button {
    padding: 6px 10px;
    margin-right: 16px;
    background: #ddd;
    font-size: 17px;
    border: none;
    cursor: pointer;
  }

  .message-list-table {
    font-size: 12px;
  }

  .message-filters .search-container .search-span {
    margin-left: -24px;
  }

  .message-filters .search-container button:hover {
    background: #ccc;
  }

  @media screen and (max-width: 600px) {
    .message-filters .search-container {
      float: none;
    }
    .message-filters a, .message-filters input[type=text], .message-filters .search-container button {
      float: none;
      display: block;
      text-align: left;
      width: 100%;
      margin: 0;
      padding: 14px;
    }
    .message-filters input[type=text] {
      border: 1px solid #ccc;
    }
  }
</style>

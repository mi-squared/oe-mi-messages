<template>
    <div class="message-list">
      <MessageList :messages="messages"/>
    </div>
</template>

<script>
import MessageList from '../MessageList'
export default {
  name: 'PageViewMessageList',
  components: { MessageList },
  props: {
    listId: {
      required: true,
      type: String
    }
  },
  computed: {
    messages () {
      if (this.$store.state.messageFilters[this.listId]) {
        return Object.keys(this.$store.state.messageFilters[this.listId].messages).map(messageId => this.$store.state.messages[messageId])
      } else {
        return []
      }
    }
  }
}
</script>

<style scoped lang="scss">

</style>

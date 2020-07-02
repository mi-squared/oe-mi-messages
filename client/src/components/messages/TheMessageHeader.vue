<template>
  <div class="message-header-row">
    <nav class="navbar navbar-expand-md message-bar">
      <div class="triangle-drop"></div>
      <h2 class="mb-4">{{message.subject}}</h2>
      <div>{{from.name}} [{{from.email}}]</div>
      <div>
        <AppDate :timestamp="message.createdAt"/>
      </div>
      <button class="btn-secondary"><i class="fa fa-reply"></i><span>Reply</span></button>
    </nav>
  </div>
</template>

<script>
import AppDate from '../AppDate'
export default {
  name: 'TheMessageHeader',
  components: { AppDate },
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
    }
  }
}
</script>

<style scoped>

</style>

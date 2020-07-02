<template>
  <table class="table">
    <thead>
    <tr>
      <th><input type="checkbox"/></th>
      <th>Status</th>
      <th>Message</th>
      <th>Indicators</th>
      <th>Participants</th>
      <th>Created</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <MessageListItem
      v-for="message in messages"
      :message="message"
      :key="message['.key']"
    />
    </tbody>
  </table>
</template>

<script>
import MessageListItem from './MessageListItem'
export default {
  name: 'MessageList',
  components: {
    MessageListItem
  },
  props: {
    messages: {
      required: true,
      type: Array
    }
  }
}
</script>

<style scoped lang="scss">
  .table {
    // table-layout: fixed;
  }
  .table th {
    border-top: 0;
    font-size: smaller;
  }
</style>

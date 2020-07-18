<template>
  <div class="reply-list">
    <ReplyListItem
      v-for="reply in replies"
      :reply="reply"
      :key="reply['.key']"
    />
  </div>
</template>

<script>
import ReplyListItem from './ReplyListItem'
export default {
  name: 'ReplyList',
  components: { ReplyListItem },
  props: {
    message: {
      required: true,
      type: Object
    }
  },
  computed: {
    replies () {
      if (this.message.replies) {
        return Object.keys(this.message.replies).map(replyId => this.$store.state.replies[replyId])
      } else {
        return []
      }
    }
  }
}
</script>

<style scoped>

</style>

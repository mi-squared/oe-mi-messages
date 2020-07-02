<template>
  <ul class="list-unstyled components">
    <MessageToolListItem
      v-for="messageTool in messageTools"
      :messageTool="messageTool"
      :key="messageTool['.key']"
    />
  </ul>
</template>

<script>
import MessageToolListItem from './MessageToolListItem'
export default {
  name: 'MessageToolList',
  components: { MessageToolListItem },
  props: {
    messageTools: {
      required: true,
      type: Array
    }
  }
}
</script>

<style scoped>

</style>

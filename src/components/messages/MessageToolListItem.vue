<template>
  <li class="message-tool-list-item">
    <a href="#"><span class="fa mr-3" :class="messageTool.faIcon"></span> {{messageTool.name}}</a>
  </li>
</template>

<script>
export default {
  name: 'MessageToolListItem',
  props: {
    messageTool: {
      required: true,
      type: Object
    }
  }
}
</script>

<style scoped>

</style>

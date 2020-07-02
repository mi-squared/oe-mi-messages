<template>
  <ul class="list-unstyled components">
    <MessageFilterListItem
      v-for="messageFilter in messageFilters"
      :messageFilter="messageFilter"
      :key="messageFilter['.key']"
    />
  </ul>
</template>

<script>
import MessageFilterListItem from './MessageFilterListItem'
export default {
  name: 'MessageFilterList',
  components: { MessageFilterListItem },
  props: {
    messageFilters: {
      required: true,
      type: Array
    }
  }
}
</script>

<style scoped>

</style>

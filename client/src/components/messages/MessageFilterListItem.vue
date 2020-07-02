<template>
  <li class="message-filter-list-item">
    <router-link :to="{name: 'PageMessageListView', params: {listId: messageFilter['.key']}}">
      <span class="fa mr-3" :class="messageFilter.faIcon"></span> {{messageFilter.name}}
    </router-link>
  </li>
</template>

<script>
export default {
  name: 'MessageFilterListItem',
  props: {
    messageFilter: {
      required: true,
      type: Object
    }
  }
}
</script>

<style scoped>

</style>

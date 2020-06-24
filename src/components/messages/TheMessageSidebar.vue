<template>
  <nav id="sidebar" class="">
    <!--    <div class="message-list-controls"><span class="message-control-menu fa fa-chevron-left"></span> <span class="message-control-menu fa fa-ellipsis-v"></span></div>-->
    <nav class="navbar navbar-expand-md message-list-controls">
      <h1>Filters</h1>
      <span class="message-control-menu fa fa-chevron-left"></span>
      <span class="message-control-menu fa fa-ellipsis-v"></span>
    </nav>
    <MessageFilterList :messageFilters="messageFilters"/>
    <div class="message-list-header-item">Tools</div>
    <MessageToolList :messageTools="messageTools"/>
  </nav>
</template>

<script>
import MessageFilterList from './MessageFilterList'
import MessageToolList from './MessageToolList'

export default {
  name: 'TheMessageSidebar',
  components: {
    MessageToolList,
    MessageFilterList
  },
  computed: {
    messageFilters () {
      return Object.values(this.$store.state.messageFilters)
    },
    messageTools () {
      return Object.values(this.$store.state.messageTools)
    }
  }
}
</script>

<style scoped>
  .message-list-header-item {
    padding: 15px 30px;
    display: block;
    color: gray;
    background-color: rgba(0, 0, 0, 0.05);
  }

  /*.message-list-controls {*/
  /*  padding: 15px 30px;*/
  /*  display: flex;*/
  /*  flex-flow: row-reverse;*/
  /*  color: gray;*/
  /*  background-color: white;*/
  /*  box-shadow: 0px 0px 0px 1px #fff;*/
  /*  min-height: 47px;*/
  /*}*/

  .message-list-controls {
    line-height: 1.8;
    padding: 0;
    background-color: white;
    box-shadow: 0px 0px 0px 1px #fff;
    border-bottom: 1px solid rgba(200, 200, 200, 0.7);
  }

  .message-control-menu {

  }

</style>

<template>
  <nav id="sidebar" class="">
    <!--    <div class="message-list-controls"><span class="message-control-menu fa fa-chevron-left"></span> <span class="message-control-menu fa fa-ellipsis-v"></span></div>-->
    <nav class="navbar navbar-expand-md message-list-controls">
      <h1>Folders</h1>
    </nav>
    <MessageFilterList :messageFilters="messageFilters"/>
    <div class="message-list-header-item">Teams</div>
    <MessageFilterList :messageFilters="teams"/>
  </nav>
</template>

<script>
import MessageFilterList from './MessageFilterList'

export default {
  name: 'TheMessageSidebar',
  components: {
    MessageFilterList
  },
  computed: {
    teams () {
      return Object.values(this.$store.state.messageFilters).filter(messageFilter => {
        if (messageFilter.type === 'team') {
          return true
        } else {
          return false
        }
      })
    },
    messageFilters () {
      return Object.values(this.$store.state.messageFilters).filter(messageFilter => {
        if (messageFilter.type === 'folder') {
          return true
        } else {
          return false
        }
      })
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

  h1 {
    margin-left: 28px;
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

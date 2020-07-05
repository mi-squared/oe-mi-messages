<template>
  <div id="messages">
    <TheMessageTabBar :openMessages="openMessages"/>
    <div class="flex-container mx-2222">
      <TheMessageSidebar/>
      <div id="message-content-area" class="container-fluid">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
import TheMessageTabBar from '../components/messages/TheMessageTabBar'
import TheMessageSidebar from '../components/messages/TheMessageSidebar'
export default {
  name: 'Messages',
  components: {
    TheMessageSidebar,
    TheMessageTabBar
  },
  computed: {
    openMessages () {
      let openMessages = []
      if (this.$store.state.userState.openTabs) {
        openMessages = Object.keys(this.$store.state.userState.openTabs).map(messageId => this.$store.state.messages[messageId])
      }
      return openMessages
    }
  },
  created () {
    this.$store.dispatch('fetchAllMessageFilters')
      .then(messageFilters => {
        console.log(messageFilters)
      })

    this.$store.dispatch('fetchAllUsers')
      .then(users => {
        console.log(users)
      })
  }
}
</script>

<style scoped>

  .mx-2222 {
    overflow: hidden;
  }
  #message-content-area {
    margin: 0;
    padding: 0;
  }

  #messages {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
  }
</style>

<template>
  <div class="tab-bar-row">
    <nav class="navbar navbar-expand-md tab-bar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <router-link :to="{ name: 'PageMessageCreate' }" class="nav-link d-flex">
            <div class="d-flex">
              <span>New</span>
              <span class="d-flex align-content-center p-2"><a href="#" class="tab-new"><i class="fa fa-plus"></i></a></span>
          </div>
          </router-link>
        </li>
        <TabItem
          v-for="message in openMessages"
          :tabbedMessage="message"
          :key="message['.key']"
        />
      </ul>
    </nav>
  </div>
</template>

<script>
import TabItem from './TabItem'
export default {
  name: 'TheMessageTabBar',
  components: { TabItem },
  props: {
    openMessages: {
      required: true,
      type: Array
    }
  }
}
</script>

<style scoped>
  .tab-new {
    background: rgba(0,0,0,0);
  }
</style>

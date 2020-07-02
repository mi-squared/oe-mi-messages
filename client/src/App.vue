<template>
  <div id="app">
    <div class="main-window">
      <TheAppMenu/>
      <div class="flex-container">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
@import "./src/scss/app.scss";
</style>
<script>
import TheAppMenu from './components/TheAppMenu'
export default {
  components: {
    TheAppMenu
  }
}
</script>

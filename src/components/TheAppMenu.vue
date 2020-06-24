<template>
  <nav id="app-menu" class="">
    <ul class="list-unstyled components mb-5">
      <li class="active">
        <a href="#"><span class="fa fa-home"></span> Home</a>
      </li>
      <li class="active">
        <router-link :to="{name: 'PageMessages'}"><span class="fa fa-mail-bulk"></span> Messages</router-link>
      </li>
      <li>
        <a href="#"><span class="fa fa-user"></span> Profile</a>
      </li>
      <li>
        <a href="#"><span class="fa fa-file-invoice-dollar"></span> Billing</a>
      </li>
      <li>
        <a href="#"><span class="fa fa-cogs"></span> Settings</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'TheAppMenu'
}
</script>

<style scoped>

</style>

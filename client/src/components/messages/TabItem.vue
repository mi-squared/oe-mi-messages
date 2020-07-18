<template>
  <li class="nav-item">
    <router-link :to="{name: 'PageMessageView', params: {messageId: this.tabbedMessage['.key']}}" class="nav-link d-flex">
      <div class="d-flex">
        <span class="d-flex p-2"><i class="fa fa-envelope"></i></span>
        <span>{{ this.tabbedMessage.subject }}</span>
        <span class="d-flex align-content-center p-2"><a @click.prevent="closeMessage" class="tab-closer"><i class="fa fa-times"></i></a></span>
      </div>
    </router-link>
  </li>
</template>

<script>
export default {
  name: 'TabItem',
  props: {
    tabbedMessage: {
      required: true,
      type: Object
    }
  },
  methods: {
    closeMessage () {
      this.$store.dispatch('closeMessage', this.tabbedMessage)
      if (this.$route.name === 'PageMessageView' &&
        parseInt(this.$route.params.messageId, 10) === parseInt(this.tabbedMessage['.key'], 10)) {
        this.$router.go(-1)
        // Emit the event that indicates this message tab was closed
        this.$emit('tabClosed', this.tabbedMessage['.key'])
      }
    }
  }
}
</script>

<style scoped>
  .tab-closer {
    background: rgba(0,0,0,0);
  }
</style>

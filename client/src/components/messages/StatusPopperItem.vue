<template>
<a class="status-item" @click="changeStatus()" href="#">
  <span :style="{ color: statusColor }">
    <span :class="statusIconClass"></span> {{ statusTitle }}
  </span>
</a>

</template>

<script>
export default {
  name: 'StatusPopperItem',
  props: {
    status: {
      required: true,
      type: Object
    }
  },
  methods: {
    changeStatus () {
      // Tell our parent that we were chosen! We are the new status!
      this.$emit('change-status', this.status)
    }
  },
  computed: {
    statusColor () {
      return this.status.color
    },
    statusTitle () {
      return this.status.title
    },
    statusIconClass () {
      return this.status.iconClass
    }
  }
}
</script>

<style scoped>

</style>

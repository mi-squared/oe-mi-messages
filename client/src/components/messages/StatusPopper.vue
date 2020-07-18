<template>
<div>
  <Popper ref="popper" trigger="clickToToggle" :stopPropagation="true" :options="{ placement: 'right' }">
    <div class="popper">
      <StatusPopperItem
        v-on:change-status="changeStatus"
        v-for="statusOption in statusOptions"
        :status="statusOption"
        :key="statusOption['.key']"
      />
    </div>

    <a v-if="clickText" href="#" slot="reference" class="right">{{ clickText }}</a>
    <a v-else href="#" slot="reference" class="right">
      <span class="status" :class="this.status.iconClass" :style="{ color: this.status.color }"></span>
    </a>
  </Popper>
</div>
</template>

<script>
import Popper from 'vue-popperjs'
import 'vue-popperjs/dist/vue-popper.css'
import StatusPopperItem from './StatusPopperItem'

export default {
  name: 'StatusPopper',
  components: {
    StatusPopperItem,
    Popper
  },
  props: {
    status: {
      required: false,
      type: Object
    },
    clickText: {
      required: false,
      type: String,
      default: ''
    }
  },
  data () {
    return {
      currentStatus: 'new'
    }
  },
  computed: {
    statusOptions () {
      return this.$store.state.messageOptions.statusOptions
    }
  },
  methods: {
    changeStatus (newStatus) {
      this.status = newStatus
      this.$refs.popper.doClose()
      this.$emit('change-message-status', this.status)
    }
  }
}
</script>

<style scoped lang="scss">
.status-item {
  display: block;
  padding: 4px;
  text-align: left;
}
.status-item:hover {
  background-color: lightblue;
}
</style>

<template>
    <a :title="title" href="#" class="message-list-sort-toggle" @click.prevent="toggle" ><span :class="this.statusClass" ></span></a>
</template>

<script>
export default {
  name: 'SortToggle',
  props: {
    title: {
      required: false,
      type: String
    },
    column: {
      required: true,
      type: String
    },
    initialSortDir: {
      required: true,
      type: String
    }
  },
  data () {
    return {
      sortDir: 'none',
      sortOptions: ['asc', 'desc', 'none']
    }
  },
  methods: {
    toggle () {
      const index = this.sortOptions.indexOf(this.sortDir) + 1
      const sortDir = this.sortOptions[index % this.sortOptions.length]
      this.sortDir = sortDir
      this.$emit('sort-changed', { sortDir: this.sortDir, column: this.column })
    }
  },
  computed: {
    statusClass () {
      let statusClass = ''
      if (this.sortDir === 'asc') {
        statusClass = 'fas fa-fw fa-angle-up'
      } else if (this.sortDir === 'desc') {
        statusClass = 'fas fa-fw fa-angle-down'
      } else {
        statusClass = 'fas fa-fw fa-minus'
      }

      return statusClass
    }
  },
  mounted () {
    this.sortDir = this.initialSortDir
    // Initialize our sorts
    this.$emit('sort-changed', { sortDir: this.sortDir, column: this.column })
  }
}
</script>

<style scoped>
.message-list-sort-toggle {

}
</style>

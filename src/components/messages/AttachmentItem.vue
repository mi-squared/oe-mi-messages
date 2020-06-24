<template>
  <div class="attachment badge" @click.stop="viewFile()">
    <span class="fa filename" :class="icon"></span>&nbsp;
    <span class="filename">{{ file.name }}</span>
  </div>
</template>

<script>
export default {
  name: 'AttachementItem',
  props: {
    file: {
      required: true,
      type: Object
    }
  },
  methods: {
    viewFile () {
      this.$router.push({
        name: 'PagePDFVue',
        params: {
          fileId: this.file['.key']
        }
      })
      // this.$store.dispatch('openFile', this.file)
    }
  },
  computed: {
    icon () {
      const ext = this.file.name.split('.').pop().toLowerCase()
      let icon = 'fa-file'
      if (ext === 'pdf') {
        icon = 'fa-file-pdf'
      }
      return icon
    }
  }
}
</script>

<style scoped>
.filename {
  color: white;
}
</style>

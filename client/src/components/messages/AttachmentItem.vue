<template>
  <div class="attachment badge" @click.stop="viewFile()">
    <span class="fa filename" :class="icon"></span>&nbsp;
    <span class="filename">{{ attachment.name }}</span>
  </div>
</template>

<script>
export default {
  name: 'AttachmentItem',
  props: {
    attachment: {
      required: true,
      type: Object
    }
  },
  methods: {
    viewFile () {
      this.$router.push({
        name: 'PagePDFVue',
        params: {
          fileId: this.attachment['.key']
        }
      })
      // this.$store.dispatch('openFile', this.file)
    }
  },
  computed: {
    icon () {
      const ext = this.attachment.name.split('.').pop().toLowerCase()
      let icon = 'fa-file'
      if (ext === 'pdf') {
        icon = 'fa-file'
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

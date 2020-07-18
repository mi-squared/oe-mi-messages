<template>
  <div class="attachment badge" :class="[ attachment.type ]" @click.stop="viewAttachment()">
    <span class="fa attachment-name" :class="icon"></span>&nbsp;
    <span class="attachment-name">{{ attachment.name }}</span>
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
    viewAttachment () {
      // If the file is a PDF, open the viewer
      if (this.attachment.type === 'document') {
        // make the call to service, and get PDF
        this.$store.dispatch('fetchPreview', { attachment: this.attachment })
          .then(file => {
            this.$router.push({
              name: 'PagePDFVue',
              params: {
                fileId: file['.key']
              }
            })
          })
      }
      // this.$store.dispatch('openFile', this.file)
    }
  },
  computed: {
    icon () {
      let icon = 'fa-file'
      if (this.attachment.type === 'document') {
        icon = 'fa-file'
      } else if (this.attachment.type === 'patient') {
        icon = 'fa-user'
      }
      return icon
    }
  }
}
</script>

<style scoped>
  .attachment-name {
    color: white;
  }
</style>

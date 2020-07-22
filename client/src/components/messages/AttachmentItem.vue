<template>
  <div class="attachment badge" :class="[ attachment.type ]" @click.stop="viewAttachment()">
    <span class="fa attachment-name" :class="icon"></span>&nbsp;
    <span class="attachment-name">{{ attachment.name }}</span>
  </div>
</template>

<script>
// import axios from 'axios'

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
        this.$router.push({
          name: 'PagePDFVue',
          params: {
            attachmentId: this.attachment['.key']
          }
        })
      } else if (this.attachment.type === 'patient') {
        top.restoreSession()
        top.RTop.location = '/interface/patient_file/summary/demographics.php?set_pid=' + encodeURIComponent(this.attachment.properties.pid)
      }
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

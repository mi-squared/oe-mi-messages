<template>
  <div class="message-view">
    <form @submit.prevent="save">
      <div class="form-group">
        <input v-model="subject" type="text" id="message_subject" class="form-input" name="subject">
      </div>

      <div class="form-group">
        <FieldUserSelect @updateRecipients="recipients = $event"></FieldUserSelect>
      </div>

      <div class="form-group">
        <TextEditor @updateContent="body = $event"/>
      </div>

      <div class="btn-group">
        <button class="btn btn-ghost">Cancel</button>
        <button class="btn btn-blue" type="submit" name="Publish">Publish </button>
      </div>
    </form>
  </div>
</template>

<script>
import FieldUserSelect from '../FieldUserSelect'
import TextEditor from '../../TextEditor'
export default {
  name: 'PageMessageCreate',
  components: { FieldUserSelect, TextEditor },
  data () {
    return {
      subject: '',
      body: '',
      recipients: [],
      attachments: []
    }
  },
  methods: {
    save () {
      const message = {
        subject: this.subject,
        body: this.body,
        recipients: this.recipients
      }
      this.$emit('save', { message })
      this.$store.dispatch('createMessage', message).then(message => {
        // Redirect to the "view" of this message
        this.$router.push({ name: 'PageMessageView', params: { messageId: message['.key'] } })

        // Tell vue to create an open tab
        this.$store.dispatch('openMessage', message)
      })
    }
  }
}
</script>

<style scoped>

</style>

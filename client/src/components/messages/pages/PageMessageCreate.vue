<template>
  <div class="message-view-container">
    <form @submit.prevent="save">
      <div class="form-group">
        <input placeholder="Subject..." v-model="subject" type="text" id="message-subject" class="form-input" name="subject">
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
  .message-view-container {
    height: 100%;
    padding: 44px;
  }
  #message-subject {
    min-width: 450px;
    line-height: 1.8em;
    padding: 4px;
  }
</style>

<template>
    <div class="user-select">
      <vue-tags-input
        placeholder="Select users or teams"
        v-model="tag"
        :tags="tags"
        :allow-edit-tags="false"
        :autocomplete-items="filteredUsers"
        class="tags-input"
        @tags-changed="updateRecipients"
      >
        <div
          slot="autocomplete-item"
          slot-scope="props"
          class="my-item"
          @click="props.performAdd(props.item)"
        >
          <img class="avatar" :src="props.item.avatar">
          {{ props.item.text }}
        </div>
        <div
          slot="tag-left"
          slot-scope="props"
          class="my-tag-left"
          @click="props.performOpenEdit(props.index)"
        >
          <img class="avatar" :src="props.tag.avatar">
        </div>
      </vue-tags-input>
    </div>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input'

export default {
  name: 'FieldUserSelect',
  components: {
    VueTagsInput
  },
  props: {
    users: {
      required: false,
      type: Array
    }
  },
  data () {
    return {
      tag: '',
      separators: [';', ','],
      tags: []
    }
  },
  methods: {
    updateRecipients (newTags) {
      this.tags = newTags
      this.$emit('updateRecipients', this.tags)
    }
  },
  computed: {
    // items () {
    //   return this.icons.filter(i => {
    //     return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1
    //   })
    // },
    filteredUsers () {
      return Object.values(this.$store.state.users).map(user => {
        return {
          text: user.username,
          avatar: user.avatar,
          id: user['.key']
        }
      })
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div class="reply-item">
    <div class="media g-mb-30 media-comment">
      <img class="d-flex g-width-50 g-height-50 rounded-circle g-mt-3 g-mr-15"
           :src="from.avatar" :alt="from.name">
      <div class="media-body u-shadow-v18 g-bg-secondary g-pa-30">
        <div class="g-mb-15">
          <h5 class="h5 g-color-gray-dark-v1 mb-0">{{ from.name }}</h5>
          <span class="g-color-gray-dark-v4 g-font-size-12"><AppDate :timestamp="reply.createdAt"/> </span>
        </div>
        <p>{{ reply.body }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import AppDate from '../AppDate'
export default {
  name: 'ReplyListItem',
  components: { AppDate },
  props: {
    reply: {
      required: true,
      type: Object
    }
  },
  computed: {
    from () {
      return this.$store.state.users[this.reply.userId]
    }
  }
}
</script>

<style scoped lang="scss">
  @media (min-width: 0) {
    .g-mr-15 {
      margin-right: 1.07143rem !important;
    }
  }

  @media (min-width: 0) {
    .g-mt-3 {
      margin-top: 0.21429rem !important;
    }
  }

  .g-height-50 {
    height: 50px;
  }

  .g-width-50 {
    width: 50px !important;
  }

  @media (min-width: 0) {
    .g-pa-30 {
      padding: 2.14286rem !important;
    }
  }

  .g-bg-secondary {
    background-color: #fafafa !important;
  }

  .u-shadow-v18 {
    box-shadow: 0 5px 10px -6px rgba(0, 0, 0, 0.15);
  }

  .g-color-gray-dark-v4 {
    color: #777 !important;
  }

  .g-font-size-12 {
    font-size: 0.85714rem !important;
  }

  .media-comment {
    margin-top: 20px;
  }

  .media-body {
    margin-right: 50px;
  }
</style>

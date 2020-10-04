<template>
  <span :title="timestamp | humanFriendlyDate">{{timestamp | humanFriendlyDate}}</span>
</template>

<script>
import moment from 'moment'

export default {
  name: 'AppDate',
  props: {
    timestamp: {
      required: true,
      type: String
    }
  },
  filters: {
    humanFriendlyDate (ts) {
      let formatted = ''
      const rightNow = moment().toISOString()
      const timestamp = moment(ts).toISOString()
      if (moment(timestamp).isSame(rightNow, 'hour')) {
        formatted = moment(timestamp).fromNow()
      } else if (moment(timestamp).isSame(rightNow, 'day')) {
        formatted = moment(timestamp).format('[Today at] h:mmA')
      } else if (moment(timestamp).isSame(rightNow, 'week')) {
        formatted = moment(timestamp).format('ddd h:mmA')
      } else if (moment(timestamp).isSame(rightNow, 'month')) {
        formatted = moment(timestamp).format('MMM D')
      } else if (moment(timestamp).isSame(rightNow, 'year')) {
        formatted = moment(timestamp).format('MMM D')
      } else {
        formatted = moment(timestamp).format('MMM D, YYYY')
        // formatted = moment(timestamp).format('llll')
      }

      return formatted
    }
  }
}
</script>

<style scoped>

</style>

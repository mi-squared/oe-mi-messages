<template>
  <span class="team badge">#{{team.name}}</span>
</template>

<script>
export default {
  name: 'AppTeam',
  props: {
    team: {
      required: true,
      type: Object
    }
  }
}
</script>

<style scoped>
.team.badge {
  background-color: rgba(242,196,182,0.5);
}
</style>

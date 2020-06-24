<template>
  <div class="badge bg-gray">{{tag.name}}</div>
</template>

<script>
export default {
  name: 'TagItem',
  props: {
    tag: {
      required: true,
      type: Object
    }
  }
}
</script>

<style scoped>

</style>

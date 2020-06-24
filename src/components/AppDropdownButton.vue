<template>
  <div class="dropdown">
    <div class="dropdown__header" @click.prevent="toggleDropdown($event)">
      <span>Header</span> text
      <i class="fa fa-angle-down" aria-hidden="true"></i>
      <i class="fa fa-angle-up" aria-hidden="true"></i>
    </div>

    <div class="dropdown__content">
      <ul>
        <li>Lorem ipsum</li>
        <li>Lorem ipsum dolor sit</li>
        <li>Lorem ipsum dolor</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppDropdownButton',
  data: function () {
    return {
      isOpen: false
    }
  },
  methods: {
    toggleDropdown (event) {
      event.currentTarget.classList.toggle('is-active')
    }
  }
}
</script>

<style scoped lang="scss">
  .dropdown {
    &__header {
      cursor: pointer;
      line-height: 1.8em;
      padding-left: 10px;
      padding-right: 10px;
      position: relative;
      text-overflow: ellipsis;
      i.fa {
        margin-left: 6px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        transition: opacity .3s;
        &.fa-angle-up {
          opacity: 0;
        }
      }
      &.is-active {
        i.fa {
          &.fa-angle-up {
            opacity: 1;
          }
          &.fa-angle-down {
            opacity: 0;
          }
        }
        + .dropdown__content {
          height: auto;
          opacity: 1;
          visibility: visible;
          position: absolute !important;
        }
      }
    }
    &__content {
      height: 0;
      opacity: 0;
      overflow: hidden;
      padding: 15px 10px;
      transition: opacity .3s;
      visibility: hidden;
      position: absolute !important;
    }
  }
</style>

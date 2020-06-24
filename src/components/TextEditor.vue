<template>
  <div class="text-editor" @click="editorClicked">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="text-editor-menu-bar">
        <ul class="button-bar">
          <li class="item">
            <a class="link" :class="{ 'is-active': isActive.bold() }" @click="commands.bold">
              <i class="fa fa-bold"></i>
            </a>
          </li>
          <li class="item">
            <a class="link" :class="{ 'is-active': isActive.bold() }" @click="commands.italic">
              <i class="fa fa-italic"></i>
            </a>
          </li>
          <li class="item">
            <a class="link" :class="{ 'is-active': isActive.bold() }" @click="commands.underline">
              <i class="fa fa-underline"></i>
            </a>
          </li>
        </ul>
      </div>
    </editor-menu-bar>
    <div ref="editor" class="text-editor-textarea">
      <editor-content :editor="editor" />
    </div>
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import { Bold, Italic, Underline } from 'tiptap-extensions'
export default {
  name: 'TextEditor',
  components: {
    EditorContent,
    EditorMenuBar
  },
  methods: {
    editorClicked () {
      this.editor.focus()
    }
  },
  data () {
    return {
      // Create an `Editor` instance with some default content. The editor is
      // then passed to the `EditorContent` component as a `prop`
      editor: new Editor({
        content: '',
        autoFocus: true,
        extensions: [
          new Bold(),
          new Italic(),
          new Underline()
        ]
      }).on('update', ({ getHTML }) => {
        const newContent = getHTML()
        this.$emit('updateContent', newContent)
      })
    }
  },
  beforeDestroy () {
    // Always destroy your editor instance when it's no longer needed
    this.editor.destroy()
  }
}
</script>

<style scoped>

  ul.button-bar {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
  }

  li {
    float: left;
    display: inline-block;
  }

  li a {
    display: block;
    text-align: center;
    padding: 4px 12px;
    text-decoration: none;
  }

  li a:hover {
    background-color: yellow;
  }

  .text-editor {
    width: 100%;
    padding: 0;
    margin-right: 0;
    margin-left: 0;
    border-width: .2rem;
  }

  .text-editor-menu-bar {
    border: 1px solid gray;
  }

  .text-editor-textarea {
    min-height: 240px;
    padding: 8px;
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    border-bottom: 1px solid gray;
  }
</style>

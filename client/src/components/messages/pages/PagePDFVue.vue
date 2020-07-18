<template>
  <div class="fullscreen">
    <nav class="navbar navbar-dark sticky-top">
      <h1 class="navbar-brand">{{ filename }}</h1>
      <ul class="nav navbar-nav ml-auto">
        <li v-if="edit"><a href="#" @click.prevent="doView()"><span class="fas fa-eye"></span></a></li>
        <li v-else><a href="#" @click.prevent="doEdit()"><span class="fas fa-edit"></span></a></li>
        <li><a href="#" @click.prevent="downloadWithAxios()"><span class="fas fa-download"></span></a></li>
        <li><a href="#" @click.prevent="$refs.pdf[0].print()"><span class="fas fa-print"></span></a></li>
        <li><a href="#" @click.prevent ="closeFile()"><span class="fas fa-times"></span></a></li>
      </ul>
    </nav>
    <div class="canvas-container">
      <div v-if="!edit">
        <pdf
          ref="pdf"
          class="canvas"
          v-for="i in numPages"
          :key="i"
          :src="url"
          :page="i"
        ></pdf>
      </div>
      <div v-else>
        <tinymce
          id="tinymce-editor"
          @on-save="save()"
          v-model="editorContent"
          :plugins="plugins"
          :toolbar1="toolbar1"
          :toolbar2="toolbar2"
          :other_options="tinyOptions"
        >
        </tinymce>
      </div>
    </div>
  </div>
</template>

<script>

import pdf from 'vue-pdf'
import axios from 'axios'
import tinymce from 'vue-tinymce-editor'

var loadingTask = ''

export default {
  components: {
    tinymce,
    pdf
  },
  props: {
    fileId: {
      required: true,
      type: String
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      numPages: undefined,
      editorContent: '',
      tinyOptions: {
        height: '600px',
        width: '100%',
        menubar: false
      },
      plugins: [
        'save advlist autolink lists image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
      ],
      toolbar1: 'save | undo redo | formatselect | ' +
        'bold italic backcolor forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
      toolbar2: ''
    }
  },
  computed: {
    attachment () {
      return this.$store.state.attachments[this.file.attachmentId]
    },
    file () {
      return this.$store.state.files[this.fileId]
    },
    url () {
      const url = this.file.pointer
      return url
      // return '/PT_Complex.pdf'
    },
    htmlValue () {
      // Get the HTML value of content
      return ''
    },
    filename () {
      return this.attachment.name
    }
  },
  watch: {
    fileId: function (newVal, oldVal) {
      if (this.url && this.edit === false) {
        loadingTask = pdf.createLoadingTask(this.url)
        loadingTask.promise.then(pdf => {
          this.numPages = pdf.numPages
        })
      } else if (this.url && this.edit === true) {
        axios.get(this.url).then(response => {
          this.editorContent = response.data
        })
      }
    }
  },
  methods: {
    closeFile () {
      if (this.edit) {
        this.$router.go(-2)
      } else {
        this.$router.back()
      }
    },

    doEdit () {
      // make the call to service, and get HTML
      this.$store.dispatch('fetchHtml', { attachment: this.attachment })
        .then(file => {
          this.$router.push({
            name: 'PagePDFEdit', // This is defined in router, basically this page with edit mode
            params: {
              fileId: file['.key'],
              edit: true
            }
          })
        })
    },

    doView () {
      this.$router.back()
    },

    save () {
      this.$store.dispatch('pushHtml', {
        file: this.file,
        content: this.editorContent
      })
    },

    forceFileDownload (response) {
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', this.filename + '.' + this.file.type) // or any other extension
      document.body.appendChild(link)
      link.click()
    },

    downloadWithAxios () {
      axios({
        method: 'get',
        url: this.url,
        responseType: 'arraybuffer'
      })
        .then(response => {
          this.forceFileDownload(response)
        })
        .catch(() => console.log('error occured'))
    }
  },
  mounted () {
    // TODO This was global before, may not work??
    if (this.url && this.edit === false) {
      loadingTask = pdf.createLoadingTask(this.url)
      loadingTask.promise.then(pdf => {
        this.numPages = pdf.numPages
      })
    } else if (this.url) {
      axios.get(this.url).then(response => {
        this.editorContent = response.data
      })
    }
  }
}

</script>

<style scoped>
  #tinymce-editor > div {
    height: 100% !important;
    overflow: hidden !important;
  }

  .fullscreen {
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    margin: 0;
    padding: 0;
    background-color: rgba(0,0,0,0.64);
  }
  .navbar-nav {
    list-style: none;
    display: inline;
  }
  .navbar-nav li {
    display: inline;
  }
  .navbar li a {
    padding: 16px;
    color: white;
    font-size: 1.8em ;
  }

  .canvas-container {
    height: 100%;
    text-align: center;
    overflow-y: auto;
    margin: auto;
  }
  .canvas {
    padding-bottom: 20px;
    margin: auto;
    width: 75%;
    display: inline;
    direction: ltr;
  }
</style>

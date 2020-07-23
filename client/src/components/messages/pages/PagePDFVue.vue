<template>
  <div class="fullscreen">
    <nav class="navbar navbar-dark sticky-top">
      <h1 class="navbar-brand">{{ filename }}</h1>
      <ul class="nav navbar-nav ml-auto">
        <li v-if="isEditMode"><a href="#" @click.prevent="doView()"><span class="fas fa-eye"></span></a></li>
        <li v-else><a href="#" @click.prevent="doEdit()"><span class="fas fa-edit"></span></a></li>
        <li>
          <Popper ref="download-popper" trigger="clickToToggle" :stopPropagation="true" :options="{ placement: 'bottom' }">
            <div class="popper">
              <a href="#" class="popper-item" @click.prevent="downloadPdfWithAxios()">PDF</a>
              <a href="#" class="popper-item" @click.prevent="downloadDocWithAxios()">MS Word</a>
            </div>

            <a href="#" slot="reference"><span class="fas fa-download"></span></a>
          </Popper>
        </li>
        <li><a href="#" @click.prevent="$refs.pdf[0].print()"><span class="fas fa-print"></span></a></li>
        <li><a href="#" @click.prevent ="closeFile()"><span class="fas fa-times"></span></a></li>
      </ul>
    </nav>
    <div class="canvas-container">
      <div v-if="!isEditMode">
        <pdf
          ref="pdf"
          class="canvas"
          v-for="i in numPages"
          :key="i"
          :src="pdfFile.properties.url"
          :page="i"
        ></pdf>
      </div>
      <div v-else>
        <tinymce
          ref="tinymce"
          id="tinymce-editor"
          @on-save="save"
          :value="editorContent"
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
import Popper from 'vue-popperjs'

var loadingTask = ''

export default {
  components: {
    tinymce,
    pdf,
    Popper
  },
  props: {
    attachmentId: {
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
      isEditMode: false,
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
      return this.$store.state.attachments[this.attachmentId]
    },
    pdfFile () {
      return this.getFileOfType('pdf')
    },
    htmlFile () {
      return this.getFileOfType('html')
    },
    docFile () {
      let docfile = this.getFileOfType('doc')
      if (!docfile) {
        docfile = this.getFileOfType('docx')
      }
      return docfile
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
    isEditMode: function (newVal) {
      const vm = this
      if (newVal === false && this.pdfFile.properties.url) {
        loadingTask = pdf.createLoadingTask(this.pdfFile.properties.url)
        loadingTask.promise.then(pdf => {
          vm.numPages = pdf.numPages
        })
      } else if (newVal === true && this.htmlFile.properties.url) {
        axios.get(this.htmlFile.properties.url).then(response => {
          vm.editorContent = response.data
          // vm.$refs.tinymce.value = response.data
        })
      }
    }
  },
  methods: {
    getFileOfType (type) {
      let theFile = null
      Object.values(this.attachment.files).forEach(fileId => {
        const aFile = this.$store.state.files[fileId]
        if (aFile.type === type &&
          aFile.revision === this.attachment.revision) {
          theFile = aFile
        } else if (aFile.type === type &&
          aFile.revision === this.attachment.revision) {
          theFile = aFile
        }
      })
      return theFile
    },
    closeFile () {
      this.$router.back()
    },

    doEdit () {
      this.isEditMode = true
    },

    doView () {
      this.isEditMode = false
    },

    save (content) {
      this.editorContent = content
      this.$store.dispatch('pushHtml', {
        file: this.htmlFile,
        content: content
      }).then(attachment => {
        console.log('revision is now at:' + attachment.revision)
      })
    },

    forceFileDownload (response, type) {
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', this.filename + '.' + type) // or any other extension
      document.body.appendChild(link)
      link.click()
    },

    downloadPdfWithAxios () {
      axios({
        method: 'get',
        url: this.pdfFile.properties.url,
        responseType: 'arraybuffer'
      }).then(response => {
        this.forceFileDownload(response, this.pdfFile.type)
      }).catch(error => {
        console.log(error)
      })
    },

    downloadDocWithAxios () {
      axios({
        method: 'get',
        url: this.docFile.properties.url,
        responseType: 'arraybuffer'
      }).then(response => {
        this.forceFileDownload(response, this.docFile.type)
      }).catch(error => {
        console.log(error)
      })
    }
  },
  mounted () {
    this.isEditMode = this.edit
    // TODO This was global before, may not work??
    if (this.isEditMode === false && this.pdfFile.properties.url) {
      loadingTask = pdf.createLoadingTask(this.pdfFile.properties.url)
      loadingTask.promise.then(pdf => {
        this.numPages = pdf.numPages
      })
    } else if (this.isEditMode === true && this.htmlFile.properties.url) {
      axios.get(this.htmlFile.properties.url).then(response => {
        this.editorContent = response.data
      })
    }
  }
}

</script>

<style scoped lang="scss">
  .popper a.popper-item {
    color: $primary;
    display: block;
  }

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

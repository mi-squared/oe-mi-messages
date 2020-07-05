<template>
  <div class="fullscreen">
    <nav class="navbar navbar-dark sticky-top">
      <h1 class="navbar-brand">PDF.js 'Hello, world!' example</h1>
      <ul class="nav navbar-nav ml-auto">
        <li><a href="#" @click.prevent ="closeFile()"><span class="fas fa-times"></span></a></li>
      </ul>
    </nav>
    <div class="canvas-container">
      <canvas class="canvas" v-for="page in pagesArray" :key="page" :id="`the-canvas-${page}`"></canvas>
    </div>
  </div>
</template>

<script>
import pdfjs from 'pdfjs-dist/webpack'
export default {
  name: 'PageFileView',
  props: {
    url: {
      required: true,
      type: String
    }
  },
  data: function () {
    return {
      pdf: {}
    }
  },
  methods: {
    closeFile () {
      this.$router.back()
    },
    getPage (pageNumber) {
      this.pdf.getPage(pageNumber).then(function (page) {
        console.log('Page loaded')

        var scale = 1.5
        var viewport = page.getViewport({ scale: scale })

        // Prepare canvas using PDF page dimensions
        var canvas = document.getElementById('the-canvas-' + pageNumber)
        var context = canvas.getContext('2d')
        canvas.height = viewport.height
        canvas.width = viewport.width

        // Render PDF page into canvas context
        var renderContext = {
          canvasContext: context,
          viewport: viewport
        }
        var renderTask = page.render(renderContext)
        renderTask.promise.then(function () {
          console.log('Page rendered')
        })
      })
    }
  },
  computed: {
    pagesArray () {
      return [...Array(this.pdf.numPages).keys()].map(x => ++x)
    }
  },
  mounted () {
    // If absolute URL from the remote server is provided, configure the CORS
    // header on that server.
    var url = this.url

    // The workerSrc property shall be specified.
    pdfjs.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js'

    // Asynchronous download of PDF
    var loadingTask = pdfjs.getDocument(url)
    var vm = this
    loadingTask.promise.then(function (pdfJS) {
      console.log('PDF loaded')

      vm.pdf = pdfJS

      // Fetch the first page
      vm.getPage(1)
    },
    function (reason) {
      // PDF loading error
      console.error(reason)
    })
  }
}
</script>

<style scoped>
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
  .navbar a {
    color: white;
    font-size: larger;
  }
  .canvas-container {
    height: 100%;
    text-align: center;
    overflow-y: auto;
    margin: auto;
  }
  .canvas {
    display: inline;
    direction: ltr;
  }
</style>

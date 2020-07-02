<template>
  <div class="fullscreen">
    <nav class="navbar navbar-dark sticky-top">
      <h1 class="navbar-brand">Filename goes here</h1>
      <ul class="nav navbar-nav ml-auto">
        <li><a href="#" @click.prevent="downloadWithAxios()"><span class="fas fa-download"></span></a></li>
        <li><a href="#" @click.prevent="$refs.pdf[0].print()"><span class="fas fa-print"></span></a></li>
        <li><a href="#" @click.prevent ="closeFile()"><span class="fas fa-times"></span></a></li>
      </ul>
    </nav>
    <div class="canvas-container">
      <pdf
        ref="pdf"
        class="canvas"
        v-for="i in numPages"
        :key="i"
        :src="src"
        :page="i"
      ></pdf>
    </div>
  </div>
</template>

<script>

import pdf from 'vue-pdf'
import axios from 'axios'

var loadingTask = pdf.createLoadingTask('http://localhost:8080/PT_Complex.pdf')

export default {
  components: {
    pdf
  },
  data () {
    return {
      src: 'http://localhost:8080/PT_Complex.pdf',
      numPages: undefined
    }
  },
  methods: {
    closeFile () {
      this.$router.back()
    },

    download () {
    },

    forceFileDownload (response) {
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', 'file.pdf') // or any other extension
      document.body.appendChild(link)
      link.click()
    },

    downloadWithAxios () {
      axios({
        method: 'get',
        url: this.src,
        responseType: 'arraybuffer'
      })
        .then(response => {
          this.forceFileDownload(response)
        })
        .catch(() => console.log('error occured'))
    }
  },
  mounted () {
    loadingTask.promise.then(pdf => {
      this.numPages = pdf.numPages
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

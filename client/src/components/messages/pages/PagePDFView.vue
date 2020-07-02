<template>
  <div class="fullscreen">
    <iframe src="/web/viewer.html?file=http://localhost:8080/PT_Complex.pdf"
            height="800px" id="fraViewDocumentsPDFViewer" runat="server" width="800px">
    </iframe>
  </div>
</template>

<script>

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

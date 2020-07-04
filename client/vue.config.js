module.exports = {
  css: {
    loaderOptions: {
      sass: {
        prependData: '@import "@/scss/_variables.scss";'
      },
      scss: {
        prependData: `@import "@/scss/_variables.scss";`
      }
    }
  },
  publicPath: '/interface/modules/custom_modules/oe-mi-messages/client',
  devServer: {
    disableHostCheck: true,
    headers: {
      "Access-Control-Allow-Origin": "*"
    },
    watchOptions: {
      aggregateTimeout: 300,
      poll: 1000
    },
    // proxy: {
    //  '/': {
    //     target: 'http://perfect-transcription:8888',
    //     changeOrigin: true
    //  }
    // }
  }
}

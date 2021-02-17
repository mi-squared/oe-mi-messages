require('dotenv').config()

let messages_base_path = '/interface/modules/custom_modules/oe-mi-messages/client'
if (process.env.APP_BASE_URL) {
  messages_base_path = process.env.APP_BASE_URL + messages_base_path
}
if (process.env.NODE_ENV === 'production') {
  messages_base_path = messages_base_path + '/dist'
}

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
  publicPath: messages_base_path,
  devServer: {
    disableHostCheck: true,
    headers: {
      'Access-Control-Allow-Origin': '*'
    },
    watchOptions: {
      aggregateTimeout: 300,
      poll: 1000
    },
    proxy: 'http://perfect-transcription:8888/openemr'
  }
}

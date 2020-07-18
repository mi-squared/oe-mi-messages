export default function createWebSocketPlugin () {
  return store => {
    // Create a new WebSocket.
    // var socketIsOpen = false
    const socket = new WebSocket('ws://localhost:8081')

    socket.onopen = function () {
      // socketIsOpen = true
      socket.send('This is a message from your client')
    }

    socket.onmessage = function (e) {
      const job = e.data
      console.log(job)
      // Get the job id from the message
      store.commit('wsReceivedData', e.data)
    }
  }
}

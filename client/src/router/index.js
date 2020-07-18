import Vue from 'vue'
import VueRouter from 'vue-router'
// import Home from '../views/Home.vue'
import PageMessages from '../views/PageMessages'
import PageMessageView from '../components/messages/pages/PageMessageView'
import PageMessageListView from '../components/messages/pages/PageMessageListView'
import PageMessageCreate from '../components/messages/pages/PageMessageCreate'
import PagePDFVue from '../components/messages/pages/PagePDFVue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    // name: 'Home',
    // component: Home
    redirect: '/messages'
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/attachment/edit/:fileId',
    name: 'PagePDFEdit',
    component: PagePDFVue,
    props: true
  },
  {
    path: '/attachment/:fileId',
    name: 'PagePDFVue',
    component: PagePDFVue,
    props: true
  },
  {
    path: '/messages',
    name: 'PageMessages',
    component: PageMessages,
    children: [
      {
        path: 'message/:messageId',
        name: 'PageMessageView',
        component: PageMessageView,
        props: true
      },
      {
        path: 'message/create',
        name: 'PageMessageCreate',
        component: PageMessageCreate
      },
      {
        path: 'list/:listId',
        name: 'PageMessageListView',
        component: PageMessageListView,
        props: true
      }
    ]
  }
]

const router = new VueRouter({
  mode: 'history',
  // mode: 'abstract',
  base: process.env.BASE_URL,
  routes
})

export default router

import Vue from 'vue'
import Routes from './routes'
import NProgress from 'nprogress'
import VueRouter from 'vue-router'
import HelperMixin from "./mixins/HelperMixin"
import RequestMixin from "./mixins/RequestMixin"

require('bootstrap')

window.Popper = require('popper.js').default

Vue.mixin(HelperMixin)
Vue.mixin(RequestMixin)

// Prevent the production tip on Vue startup
Vue.config.productionTip = false

Vue.use(VueRouter)

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: Studio.path,
})

NProgress.configure({
    showSpinner: false,
    easing: 'ease',
    speed: 300,
})

router.beforeEach((to, from, next) => {
    NProgress.start()
    next()
})

router.afterEach(() => {
    NProgress.done()
})

const app = new Vue({
    el: '#studio',
    router,
    data: {
        avatar: Studio.avatar
    }
})


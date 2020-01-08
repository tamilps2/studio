<template>
    <div>
        <page-header />

        <div class="col-xl-10 offset-xl-1 col-md-12">
            <h1>PostShow.vue</h1>
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'post-show',

        components: {
            PageHeader
        },

        data() {
            return {
                user: null,
                isReady: false,
            }
        },

        mounted() {
            this.fetchData()
        },

        methods: {
            fetchData() {
                NProgress.done()
            },
        },
    }
</script>

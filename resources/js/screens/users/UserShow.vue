<template>
    <div>
        <page-header />

        <div v-if="isReady">
            <h1 v-if="!hasErrors">UserShow.vue</h1>

            <not-found v-if="hasErrors" />
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import NotFound from '../../components/NotFound'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'user-show',

        components: {
            PageHeader,
            NotFound
        },

        data() {
            return {
                user: null,
                isReady: false,
                hasErrors: false,
            }
        },

        created() {
            this.fetchData()
        },

        methods: {
            fetchData() {
                this.request()
                    .get('/api/users/' + this.$route.params.username)
                    .then(response => {
                        this.user = response.data.user
                        this.isReady = true

                        NProgress.done()
                    })
                    .catch(error => {
                        // Add any error debugging...
                        this.$router.push(this.$route.path).catch(err => {})
                        this.isReady = true
                        this.hasErrors = true
                        NProgress.done()
                    })
            },
        },
    }
</script>

<template>
    <div>
        <vue-headful
            v-if="isReady"
            :title="user.name + ' — Studio'"
            :description="summary"
            :image="avatar"
        />

        <page-header/>

        <div v-if="isReady">
            <div v-if="!hasErrors">
                <div class="container my-5 col-xl-8 offset-xl-2 col-md-10 offset-md-1 align-items-center">
                    <div class="row">
                        <div class="col-lg-2">
                            <img :src="avatar" :alt="user.name" width="120" class="rounded-circle shadow-inner">
                        </div>
                        <div class="col-lg-10">
                            <h1 class="font-weight-bold">{{ user.name }}</h1>
                            <p class="text-muted">
                                {{ summary }}
                            </p>
                        </div>
                    </div>
                </div>

                <main role="main" class="col-xl-8 offset-xl-2 col-md-10 offset-md-1">
                    <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                        {{ trans.studio.posts.label }}
                    </h3>

                    <post-list v-if="isReady" :posts="posts"/>
                </main>
            </div>

            <not-found v-if="hasErrors"/>
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful';
    import PostList from '../../components/PostList'
    import NotFound from '../../components/NotFound'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'user-show',

        components: {
            PageHeader,
            PostList,
            NotFound,
            vueHeadful
        },

        data() {
            return {
                user: null,
                avatar: null,
                summary: null,
                posts: null,
                isReady: false,
                hasErrors: false,
                trans: JSON.parse(Studio.lang),
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
                        this.avatar = response.data.avatar
                        this.summary = response.data.summary
                        this.posts = response.data.posts

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
            }
        },
    }
</script>

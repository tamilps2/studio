<template>
    <div>
        <vue-headful
            v-if="isReady && !hasErrors"
            :title="user.name + ' — Studio'"
            :description="summary"
            :image="avatar"
        />

        <page-header></page-header>

        <div v-if="isReady">
            <div v-if="!hasErrors">
                <div class="container my-5 col-xl-8 offset-xl-2 col-md-10 offset-md-1 align-items-center">
                    <div class="row">
                        <div class="col-lg-2">
                            <img :src="avatar" :alt="user.name" width="120" class="rounded-circle shadow-inner">
                        </div>
                        <div class="col-lg-10">
                            <h1>{{ user.name }}</h1>
                            <p class="text-secondary">
                                {{ summary }}
                            </p>
                        </div>
                    </div>
                </div>

                <main role="main" class="col-xl-8 offset-xl-2 col-md-10 offset-md-1">
                    <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                        Featured
                    </h3>
                    <post-list v-if="isReady" :posts="[featuredPost]"/>

                    <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                        Latest
                    </h3>
                    <post-list v-if="isReady" :posts="posts"/>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful';
    import PostList from '../components/PostList'
    import PageHeader from '../components/PageHeader'

    export default {
        name: 'user-screen',

        components: {
            PageHeader,
            PostList,
            vueHeadful
        },

        data() {
            return {
                user: null,
                avatar: null,
                summary: null,
                posts: null,
                featuredPost: null,
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
                    .get('/studio/users/' + this.$route.params.username)
                    .then(response => {
                        this.user = response.data.user
                        this.avatar = response.data.avatar
                        this.summary = response.data.summary
                        this.featuredPost = response.data.posts.shift()
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

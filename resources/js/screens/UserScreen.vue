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
                    <div v-if="featuredPost">
                        <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                            Featured
                        </h3>
                        <post-list :posts="[featuredPost]"/>

                        <div v-if="posts.length > 0">
                            <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                                Latest
                            </h3>
                            <post-list :posts="posts"/>
                        </div>
                    </div>
                    <div v-else class="col-12">
                        <p class="lead text-muted text-center mt-5 pt-5">You have no published posts</p>
                        <p class="lead text-muted text-center mt-1">Write on the go with our mobile-ready app!</p>
                    </div>
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
                posts: [],
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
                    .get('/studio/users/' + this.$route.params.identifier)
                    .then(response => {
                        this.user = response.data.user
                        this.avatar = response.data.avatar
                        this.summary = response.data.summary
                        this.posts = response.data.posts
                        this.featuredPost = this.posts.shift() ?? null
                        this.isReady = true

                        NProgress.done()
                    })
                    .catch(error => {
                        // Add any error debugging...
                        this.hasErrors = true
                        this.$router.push({name: 'home'})

                        NProgress.done()
                    })
            }
        },
    }
</script>

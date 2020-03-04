<template>
    <div>
        <vue-headful
            title="Studio — A Laravel publishing platform"
            description="Sometimes creating a blog is easier said than done. With Canvas, it's just easier."
        />

        <page-header></page-header>
        <topic-bar v-if="isReady" :topics="topics"/>

        <div v-if="!hasErrors" class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
            <router-link v-if="featuredPost" :to="{ name: 'post', params: { username: featuredPost.user_meta.username, slug: featuredPost.slug } }" class="text-decoration-none">
                <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark" :style="featuredPost.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+featuredPost.featured_image+'); background-size: cover' : ''">
                    <div class="col-md-8 px-0">
                        <h1 class="font-italic font-serif">{{ featuredPost.title }}</h1>
                        <p v-if="featuredPost.summary" class="lead my-3">{{ featuredPost.summary }}</p>
                        <p v-else class="lead my-3">{{ featuredPost.body | trim }}</p>
                        <p class="lead mb-0">Continue reading...</p>
                    </div>
                </div>
            </router-link>

            <main role="main" class="row justify-content-between">
                <div v-if="isReady && posts.length > 0" class="col-md-8">
                    <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                        Recent posts
                    </h3>

                    <post-list :posts="posts"/>
                </div>

                <div v-if="isReady" class="col-12">
                    <div v-if="posts.length == 0 && !featuredPost">
                        <p class="lead text-muted text-center mt-5 pt-5">You have no published posts</p>
                        <p class="lead text-muted text-center mt-1">Write on the go with our mobile-ready app!</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <tag-list v-if="isReady && tags.length > 0" :tags="tags"/>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful'
    import TagList from '../components/TagList'
    import PostList from '../components/PostList'
    import TopicBar from '../components/TopicBar'
    import PageHeader from '../components/PageHeader'

    export default {
        name: 'home-screen',

        components: {
            PageHeader,
            PostList,
            TagList,
            TopicBar,
            vueHeadful
        },

        data() {
            return {
                posts: [],
                featuredPost: null,
                tags: null,
                topics: null,
                isReady: false,
                hasErrors: false,
            }
        },

        filters: {
            trim: function (value) {
                return $(value).text().substr(0, 100) + '...'
            }
        },

        mounted() {
            this.fetchData()
        },

        methods: {
            fetchData() {
                this.request()
                    .get('/studio/posts')
                    .then(response => {
                        this.posts = response.data.posts
                        this.featuredPost = this.posts.shift() ?? null
                        this.tags = response.data.tags
                        this.topics = response.data.topics
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
        }
    }
</script>

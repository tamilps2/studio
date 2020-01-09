<template>
    <div>
        <vue-headful
            v-if="isReady && !hasErrors"
            :title="topic.name + ' — Studio'"
            description="A Laravel publishing platform"
            image=""
        />

        <page-header/>
        <topic-bar v-if="isReady" :topics="topics"/>

        <div v-if="!hasErrors" class="col-xl-10 offset-xl-1 col-md-12">
            <div v-if="featuredPost"
                 class="jumbotron p-4 p-md-5 text-white rounded bg-dark"
                 :style="featuredPost.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+featuredPost.featured_image+'); background-size: cover' : ''">
                <div class="col-md-8 px-0">
                    <h1 class="font-italic font-serif">
                        <router-link
                            :to="{ name: 'post', params: { username: featuredPost.user_meta.username, slug: featuredPost.slug } }"
                            class="text-white text-decoration-none">
                            {{ featuredPost.title }}
                        </router-link>
                    </h1>
                    <p class="lead my-3">
                        <router-link
                            :to="{ name: 'post', params: { username: featuredPost.user_meta.username, slug: featuredPost.slug } }"
                            class="text-white text-decoration-none">
                            {{ featuredPost.summary }}
                        </router-link>
                    </p>
                    <p class="lead mb-0">
                        <router-link
                            :to="{ name: 'post', params: { username: featuredPost.user_meta.username, slug: featuredPost.slug } }"
                            class="text-white text-decoration-none">
                            {{ trans.studio.buttons.continue }}
                        </router-link>
                    </p>
                </div>
            </div>

            <main role="main" class="row justify-content-between">
                <div class="col-md-8">
                    <h3 class="mb-4 font-italic font-serif border-bottom pb-2">
                        {{ trans.studio.posts.label }}
                    </h3>

                    <post-list v-if="isReady" :posts="posts"/>
                </div>

                <div class="col-md-4">
                    <tag-list v-if="isReady" :tags="tags"/>
                </div>
            </main>
        </div>

        <not-found v-if="hasErrors"/>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful'
    import TagList from '../../components/TagList'
    import NotFound from '../../components/NotFound'
    import PostList from '../../components/PostList'
    import TopicBar from '../../components/TopicBar'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'post-index',

        components: {
            NotFound,
            PageHeader,
            PostList,
            TagList,
            TopicBar,
            vueHeadful
        },

        data() {
            return {
                posts: null,
                featuredPost: null,
                topic: null,
                tags: null,
                topics: null,
                isReady: false,
                hasErrors: false,
                trans: JSON.parse(Studio.lang),
            }
        },

        watch: {
            '$route.params.slug': function (slug) {
                this.fetchData()
            }
        },

        mounted() {
            this.fetchData()
        },

        methods: {
            fetchData() {
                this.request()
                    .get('/api/topics/' + this.$route.params.slug)
                    .then(response => {
                        this.posts = response.data.posts
                        this.topic = response.data.topic
                        this.featuredPost = this.posts.shift()
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
            },
        }
    }
</script>

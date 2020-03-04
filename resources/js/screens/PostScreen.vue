<template>
    <div>
        <vue-headful
            v-if="isReady && !hasErrors"
            :title="post.title + ' — Studio'"
            :description="post.summary"
            :image="post.featured_image"
            :url="meta.canonical_link"
        />

        <page-header>
            <div v-if="isReady && !hasErrors && postBelongsToAuthUser" class="dropdown" slot="actions">
                <a href="#" id="actionDropdownMenu" class="pl-0 pr-3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 24 24" class="icon-cog primary">
                        <path d="M6.8 3.45c.87-.52 1.82-.92 2.83-1.17a2.5 2.5 0 0 0 4.74 0c1.01.25 1.96.65 2.82 1.17a2.5 2.5 0 0 0 3.36 3.36c.52.86.92 1.8 1.17 2.82a2.5 2.5 0 0 0 0 4.74c-.25 1.01-.65 1.96-1.17 2.82a2.5 2.5 0 0 0-3.36 3.36c-.86.52-1.8.92-2.82 1.17a2.5 2.5 0 0 0-4.74 0c-1.01-.25-1.96-.65-2.82-1.17a2.5 2.5 0 0 0-3.36-3.36 9.94 9.94 0 0 1-1.17-2.82 2.5 2.5 0 0 0 0-4.74c.25-1.01.65-1.96 1.17-2.82a2.5 2.5 0 0 0 3.36-3.36zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdownMenu">
                    <a :href="'/' + canvasPath + '/posts/' + post.id + '/edit'" class="dropdown-item">
                        Edit post
                    </a>
                    <a :href="'/' + canvasPath + '/stats/' + post.id" class="dropdown-item">
                        View stats
                    </a>
                </div>
            </div>
        </page-header>

        <div v-if="isReady && !hasErrors">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <h1 class="text-dark font-serif pt-5 mb-4">{{ post.title }}</h1>

                <div class="media py-1">
                    <router-link :to="{ name: 'user', params: { username: username } }">
                        <img :src="avatar"
                             class="mr-3 rounded-circle shadow-inner"
                             style="width: 50px"
                             :alt="user.name">
                    </router-link>

                    <div class="media-body">
                        <router-link :to="{ name: 'user', params: { username: username } }" class="text-decoration-none">
                            <p class="my-0">{{ user.name }}</p>
                        </router-link>
                        <span class="text-secondary">{{ moment(post.published_at).format('MMM d, Y') }} — {{ post.read_time }}</span>
                    </div>
                </div>

                <img v-if="post.featured_image"
                     :src="post.featured_image"
                     class="pt-4 img-fluid w-100"
                     :alt="post.featured_image_caption"
                     :title="post.featured_image_caption">

                <p v-if="post.featured_image && post.featured_image_caption"
                   class="text-muted text-center featured-image-caption"
                   v-html="post.featured_image_caption">
                </p>

                <div class="post-content position-relative align-items-center overflow-y-visible font-serif mt-4">
                    <div v-html="post.body"></div>
                </div>

                <div v-if="tags" class="mt-5">
                    <router-link
                        v-for="(key, value) in tags"
                        :key="key"
                        :to="{ name: 'tag', params: { slug: value } }"
                        class="badge badge-light p-2 my-1 mr-2 text-decoration-none text-uppercase">
                        {{ key }}
                    </router-link>
                </div>
            </div>

            <div v-if="isReady && !hasErrors && meta.canonical_link" class="post-content position-relative align-items-center overflow-y-visible font-serif">
                <hr>
                <p class="text-center font-italic pt-3 my-5">
                    This post was originally published on <a :href="meta.canonical_link" target="_blank" rel="noopener">{{ parseURL(meta.canonical_link).hostname }}</a>
                </p>
            </div>
        </div>

        <div v-if="!hasErrors" class="read-more mt-5 container-fluid">
            <div class="row">
                <div v-if="next.post"
                     class="col-lg bg-light text-center px-lg-5 py-5"
                     :style="next.post.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+next.post.featured_image+'); background-size: cover' : ''">
                    <router-link
                        :to="{ name: 'post', params: { username: next.username, slug: next.post.slug } }"
                        class="btn btn-sm text-decoration-none text-uppercase mt-3"
                        :class="next.post.featured_image ? 'btn-outline-light' : 'btn-outline-secondary'"
                        @click.native="scrollToTop">
                        Read this next
                    </router-link>
                    <h2 class="font-serif my-3">
                        <router-link
                            :to="{ name: 'post', params: { username: next.username, slug: next.post.slug } }"
                            class="text-decoration-none"
                            :class="next.post.featured_image ? 'text-light' : 'text-dark'"
                            @click.native="scrollToTop">
                            {{ next.post.title }}
                        </router-link>
                    </h2>
                    <p class="text-lg font-serif"
                       :class="next.post.featured_image ? 'text-white-50' : 'text-secondary'">
                        {{ next.post.summary }}
                    </p>
                </div>

                <div v-if="random.post"
                     class="col-lg bg-light text-center px-lg-5 py-5"
                     :style="random.post.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+random.post.featured_image+'); background-size: cover' : ''">
                    <router-link
                        :to="{ name: 'post', params: { username: random.username, slug: random.post.slug } }"
                        class="btn btn-sm text-decoration-none text-uppercase mt-3"
                        :class="random.post.featured_image ? 'btn-outline-light' : 'btn-outline-secondary'"
                        @click.native="scrollToTop">
                        You might enjoy
                    </router-link>
                    <h2 class="font-serif my-3">
                        <router-link
                            :to="{ name: 'post', params: { username: random.username, slug: random.post.slug } }"
                            class="text-decoration-none"
                            :class="random.post.featured_image ? 'text-light' : 'text-dark'"
                            @click.native="scrollToTop">
                            {{ random.post.title }}
                        </router-link>
                    </h2>
                    <p class="text-lg font-serif" :class="random.post.featured_image ? 'text-white-50' : 'text-secondary'">
                        {{ random.post.summary }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import hljs from 'highlight.js'
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful'
    import mediumZoom from 'medium-zoom'
    import PageHeader from '../components/PageHeader'

    export default {
        name: 'post-screen',

        components: {
            PageHeader,
            vueHeadful
        },

        data() {
            return {
                user: null,
                post: null,
                tags: null,
                topic: null,
                username: null,
                avatar: null,
                meta: null,
                next: {
                    post: null,
                    username: null
                },
                random: {
                    post: null,
                    username: null
                },
                isReady: false,
                hasErrors: false,
                canvasPath: Studio.canvasPath,
            }
        },

        created() {
            this.fetchData()
        },

        updated() {
            document.querySelectorAll('.embedded_image img').forEach((image) => {
                mediumZoom(image)
            })
            document.querySelectorAll('pre').forEach((block) => {
                hljs.highlightBlock(block)
            })
        },

        watch: {
            '$route.params.slug': function (slug) {
                this.isReady = false
                this.fetchData()
            }
        },

        methods: {
            fetchData() {
                this.request()
                    .get('/studio/posts/' + this.$route.params.username + '/' + this.$route.params.slug)
                    .then(response => {
                        this.user = response.data.user
                        this.post = response.data.post
                        this.tags = response.data.tags
                        this.topic = response.data.topic
                        this.username = response.data.username
                        this.avatar = response.data.avatar
                        this.meta = response.data.meta
                        this.next.post = response.data.next.post
                        this.next.username = response.data.next.username
                        this.random.post = response.data.random.post
                        this.random.username = response.data.random.username
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

        computed: {
            postBelongsToAuthUser() {
                if (Studio.user) {
                    return this.user.id === Studio.user.id
                } else {
                    return false
                }
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/studio/variables';

    .featured-image-caption {
        text-align: center;
        color: $text-muted;
        margin-top: .5em;
        font-size: 0.9rem;
        font-family: $font-family-sans-serif, sans-serif;
    }

    .featured-image-caption a {
        text-decoration: underline;
    }

    .post-content {
        font-size: 1.1rem;
        line-height: 2;
        -webkit-box-align: center;
        -ms-flex-align: center;
    }

    .post-content p {
        margin: 2em 0 0 0;
    }

    .post-content a {
        text-decoration: underline;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3 {
        margin: 1.5em 0 0 0;
    }

    .post-content blockquote {
        margin-top: 2em;
        font-style: italic;
        font-size: 28px;
        color: $text-muted;
        padding-left: 1.5em;
        line-height: 1.5;
    }

    div.embedded_image {
        margin-top: 2em;
    }

    div.embedded_image > img {
        width: 100%;
        height: auto;
        display: block;
    }

    div.embedded_image > p {
        text-align: center;
        color: $text-muted;
        margin-top: .5em;
        font-size: 0.9rem;
        font-family: $font-family-sans-serif, sans-serif;
    }

    div.embedded_image[data-layout="wide"] img {
        max-width: 1024px;
        margin: 0 auto 30px;
    }

    div.embedded_image[data-layout=wide] {
        width: 100vw;
        position: relative;
        left: 50%;
        margin-left: -50vw;
    }

    div.post-content hr {
        border: none;
        margin: 3em 0 4em 0;
        color: $gray-900;
        letter-spacing: 1em;
        text-align: center;
    }

    div.post-content hr:before {
        content: '...';
    }

    .post-content > p > code {
        background-color: $text-muted;
    }

    pre.ql-syntax {
        margin-top: 2em;
        padding: 1em;
        border-radius: $border-radius;
    }

    @media screen and (max-width: 1024px) {
        .post-content > .embedded_image[data-layout=wide] >>> img {
            max-width: 100%
        }
    }
</style>

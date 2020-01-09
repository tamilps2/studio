<template>
    <div>
        <vue-headful
            v-if="isReady && !hasErrors"
            :title="post.title"
            :description="post.summary"
            :image="post.featured_image"
        />

        <page-header>
            <div v-if="isReady && user.id === Studio.user.id" class="dropdown" slot="actions">
                <a href="#" id="actionDropdownMenu" class="nav-link pl-0 pr-3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 24 24" class="icon-cog primary">
                        <path d="M6.8 3.45c.87-.52 1.82-.92 2.83-1.17a2.5 2.5 0 0 0 4.74 0c1.01.25 1.96.65 2.82 1.17a2.5 2.5 0 0 0 3.36 3.36c.52.86.92 1.8 1.17 2.82a2.5 2.5 0 0 0 0 4.74c-.25 1.01-.65 1.96-1.17 2.82a2.5 2.5 0 0 0-3.36 3.36c-.86.52-1.8.92-2.82 1.17a2.5 2.5 0 0 0-4.74 0c-1.01-.25-1.96-.65-2.82-1.17a2.5 2.5 0 0 0-3.36-3.36 9.94 9.94 0 0 1-1.17-2.82 2.5 2.5 0 0 0 0-4.74c.25-1.01.65-1.96 1.17-2.82a2.5 2.5 0 0 0 3.36-3.36zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdownMenu">
                    <a :href="'/' + canvasPath + '/posts/' + post.id + '/edit'" class="dropdown-item">
                        {{ trans.studio.buttons.edit }}
                    </a>
                    <a :href="'/' + canvasPath + '/stats/' + post.id" class="dropdown-item">
                        {{ trans.studio.buttons.stats }}
                    </a>
                </div>
            </div>
        </page-header>

        <div v-if="isReady && !hasErrors">
            <div class="col-xl-8 offset-xl-2 col-md-12">
                <h1 class="text-dark font-serif pt-5 mb-4">{{ post.title }}</h1>

                <div class="media py-1">
                    <router-link
                        :to="{ name: 'user', params: { username: username } }"
                        class="font-serif text-dark text-decoration-none">
                        <img :src="avatar"
                             class="mr-3 rounded-circle shadow-inner"
                             style="width: 50px"
                             :alt="user.name">
                    </router-link>

                    <div class="media-body">
                        <router-link
                            :to="{ name: 'user', params: { username: username } }"
                            class="font-serif text-dark text-decoration-none">
                            <p class="mt-0 mb-1 font-weight-bold text-dark">{{ user.name }}</p>
                        </router-link>
                        <span class="text-muted">{{ moment(post.published_at).format('M d, Y') }} — {{ post.read_time }}</span>
                    </div>
                </div>

                <img v-if="post.featured_image"
                     :src="post.featured_image"
                     class="pt-4"
                     :alt="post.featured_image_caption"
                     :title="post.featured_image_caption">

                <p v-if="post.featured_image && post.featured_image_caption"
                   class="text-muted text-center pt-3"
                   style="font-size: 0.9rem">
                    {{ post.featured_image_caption }}
                </p>

                <div class="position-relative align-items-center overflow-y-visible font-serif mt-4">
                    <div v-html="post.body"></div>
                </div>

                <div v-if="tags">
                    <router-link
                        v-for="tag in tags"
                        :key="tag.slug"
                        :to="{ name: 'tag', params: { slug: tag.slug } }"
                        class="badge badge-light p-2 my-1 text-decoration-none">
                        {{ tag.name }}
                    </router-link>
                </div>
            </div>
        </div>

        <div v-if="isReady && !hasErrors && meta.canonical_link" class="position-relative align-items-center overflow-y-visible">
            <hr>
            <p class="text-center font-italic pt-3 my-5">
                {{ trans.studio.buttons.canonical }}
                <a :href="meta.canonical_link" target="_blank" class="text-dark" rel="noopener">{{ meta.canonical_link }}</a>
            </p>
        </div>

        <div class="read-more mt-5 container-fluid">
            <div class="row">
                <div v-if="next.post"
                     class="col-lg bg-light text-center px-lg-5 py-5"
                     :style="next.post.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+next.post.featured_image+'); background-size: cover' : ''">
                    <router-link
                        :to="{ name: 'post', params: { username: next.username, slug: next.post.slug } }"
                        class="btn btn-sm text-decoration-none text-uppercase font-weight-bold mt-3"
                        :class="next.post.featured_image ? 'btn-outline-light' : 'btn-outline-secondary'"
                        @click.native="scrollToTop">
                        {{ trans.studio.buttons.next }}
                    </router-link>
                    <h2 class="font-weight-bold font-serif my-3">
                        <router-link
                            :to="{ name: 'post', params: { username: next.username, slug: next.post.slug } }"
                            class="text-decoration-none"
                            :class="next.post.featured_image ? 'text-light' : 'text-dark'"
                            @click.native="scrollToTop">
                            {{ next.post.title }}
                        </router-link>
                    </h2>
                    <p class="text-lg font-serif"
                       :class="next.post.featured_image ? 'text-white-50' : 'text-muted'">
                        {{ next.post.summary }}
                    </p>
                </div>


                <div v-if="random.post"
                     class="col-lg bg-light text-center px-lg-5 py-5"
                     :style="random.post.featured_image ? 'background: linear-gradient(rgba(0, 0, 0, 0.85),rgba(0, 0, 0, 0.85)),url('+random.post.featured_image+'); background-size: cover' : ''">
                    <router-link
                        :to="{ name: 'post', params: { username: random.username, slug: random.post.slug } }"
                        class="btn btn-sm text-decoration-none text-uppercase font-weight-bold mt-3"
                        :class="random.post.featured_image ? 'btn-outline-light' : 'btn-outline-secondary'"
                        @click.native="scrollToTop">
                        {{ trans.studio.buttons.enjoy }}
                    </router-link>
                    <h2 class="font-weight-bold font-serif my-3">
                        <router-link
                            :to="{ name: 'post', params: { username: random.username, slug: random.post.slug } }"
                            class="text-decoration-none"
                            :class="random.post.featured_image ? 'text-light' : 'text-dark'"
                            @click.native="scrollToTop">
                            {{ random.post.title }}
                        </router-link>
                    </h2>
                    <p class="text-lg font-serif"
                       :class="random.post.featured_image ? 'text-white-50' : 'text-muted'">
                        {{ random.post.summary }}
                    </p>
                </div>
            </div>

            <not-found v-if="hasErrors"/>
        </div>
    </div>
</template>

<script>
    import NProgress from 'nprogress'
    import vueHeadful from 'vue-headful'
    import NotFound from '../../components/NotFound'
    import PageHeader from '../../components/PageHeader'

    export default {
        name: 'post-show',

        components: {
            NotFound,
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
                canvasPath: Studio.path,
                trans: JSON.parse(Studio.lang),
            }
        },

        mounted() {
            this.fetchData()
        },

        watch: {
            '$route.params.slug': function (slug) {
                this.fetchData()
            }
        },

        methods: {
            fetchData() {
                this.request()
                    .get('/api/posts/' + this.$route.params.username + '/' + this.$route.params.slug)
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
                        this.$router.push(this.$route.path).catch(err => {
                        })
                        this.isReady = true
                        this.hasErrors = true
                        NProgress.done()
                    })
            }
        },
    }
</script>

<style scoped>
    .post {
        /*font-weight: 300;*/
        /*color: hsla(0, 0%, 0%, 0.9);*/
        font-size: 1.1rem;
        line-height: 2;
        position: relative !important;
        -webkit-box-align: center !important;
        -ms-flex-align: center !important;
        align-items: center !important;
        overflow-y: visible !important;
    }

    blockquote {
        margin-top: 2em;
        margin-bottom: 2em;
        font-style: italic;
        border-left: 4px solid #ccc;
        padding-left: 16px;
    }

    div.embedded_image img {
        width: 100% !important;
        height: auto;
        margin-bottom: 30px;
        display: block;
    }

    .embedded_image[data-layout="wide"] img {
        max-width: 1024px;
        margin: 0 auto 30px;
    }

    div.embedded_image[data-layout=wide] {
        width: 100vw;
        position: relative;
        left: 50%;
        margin-left: -50vw;
    }

    div.embedded_image p {
        text-align: center !important;
        color: #6c757d !important;
        font-size: 0.9rem !important;
        line-height: 1.6 !important;
        font-weight: 400 !important;
        font-family: "Karla", sans-serif;
    }

    hr {
        border: none;
        color: #111;
        letter-spacing: 1em;
        text-align: center;
    }

    .canonical {
        margin-top: 0;
        border: none;
        font-size: 17px;
        color: #111;
        letter-spacing: 1em;
        text-align: center;
    }

    .canonical:before {
        content: "...";
    }

    hr:before {
        content: '...';
    }

    p code {
        background-color: rgba(0, 0, 0, 0.05) !important;
        padding: 2px 4px !important;
        -webkit-border-radius: 3px !important;
        -moz-border-radius: 3px !important;
        border-radius: 3px !important;
    }

    pre.ql-syntax {
        background-color: rgba(0, 0, 0, 0.05) !important;
        border: none !important;
        -webkit-border-radius: 3px !important;
        -moz-border-radius: 3px !important;
        border-radius: 3px !important;
        color: #000 !important;
        overflow-x: auto !important;
        padding: 1em !important;
    }

    @media screen and (max-width: 1024px) {
        .post .embedded_image[data-layout=wide] img {
            max-width: 100%
        }
    }
</style>

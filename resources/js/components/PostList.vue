<template>
    <div>
        <div v-for="post in availablePosts">
            <h3>
                <router-link
                    :to="{ name: 'post', params: { username: post.user_meta.username, slug: post.slug } }"
                    class="font-serif text-dark text-decoration-none">
                    {{ post.title }}
                </router-link>
            </h3>
            <p class="text-muted mb-2">{{ moment(post.published_at).format('MMM D') }} — {{ post.read_time }}</p>
            <p>
                <router-link
                    :to="{ name: 'post', params: { username: post.user_meta.username, slug: post.slug } }"
                    class="text-dark text-decoration-none">
                    {{ post.summary }}
                </router-link>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'post-list',

        props: {
            posts: {
                type: Array,
                required: false,
            },
        },

        data() {
            return {
                availablePosts: this.posts,
                trans: JSON.parse(Studio.lang),
            }
        }
    }
</script>

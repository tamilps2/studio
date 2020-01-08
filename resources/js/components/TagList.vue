<template>
    <div class="p-md-4">
        <h4 class="font-italic font-serif">{{ trans.studio.tags.label }}</h4>
        <ol class="list-unstyled mb-0">
            <li v-for="tag in availableTags">
                <router-link
                    @click.native="scrollToTop"
                    :to="{ name: 'tag', params: { slug: tag.slug } }"
                    class="text-decoration-none text-secondary">
                    {{ tag.name }}
                </router-link>
            </li>
        </ol>
    </div>
</template>

<script>
    export default {
        name: 'tag-list',

        props: {
            tags: {
                type: Array,
                required: false,
            },
        },

        data() {
            return {
                availableTags: this.tags,
                trans: JSON.parse(Studio.lang),
            }
        }
    }
</script>

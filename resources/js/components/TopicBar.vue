<template>
    <div class="col-xl-10 offset-xl-1 col-md-12">
        <div class="container">
            <div class="nav-scroller position-relative overflow-y-hidden py-4 mb-3">
                <nav class="nav d-flex justify-content-between">
                    <div v-for="topic in availableTopics">
                        <router-link
                            :to="{ name: 'topic', params: { slug: topic.slug } }"
                            class="p-2 text-decoration-none">
                            {{ topic.name }}
                        </router-link>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'topic-bar',

        props: {
            topics: {
                type: Array,
                required: false,
            },
        },

        data() {
            return {
                availableTopics: this.topics
            }
        }
    }
</script>

<style scoped>
    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
</style>

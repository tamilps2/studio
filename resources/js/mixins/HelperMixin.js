export default {
    computed: {
        Studio() {
            return window.Studio
        },
    },

    methods: {
        scrollToTop() {
            window.scrollTo(0, 0);
        },
    },
}

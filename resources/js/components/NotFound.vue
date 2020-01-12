<template>
    <div class="wrapper d-flex align-items-center justify-content-center">
        <div class="code h2 px-3 text-center">404</div>
        <div class="message h3 px-3 text-center">Not Found</div>
    </div>
</template>

<script>
    export default {
        name: 'not-found',

        data() {
            return {
                user: Studio.user,
            }
        }
    }
</script>

<style scoped>
    .code {
        border-right: 2px solid #636b6f;
        color: #636b6f;
    }

    .message {
        color: #636b6f;
    }

    .wrapper {
        min-height: calc(100vh - 70px);
    }
</style>

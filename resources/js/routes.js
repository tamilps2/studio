export default [
    {
        path: '/',
        name: 'home',
        component: require('./screens/posts/PostIndex').default,
    },
    {
        path: '/tag/:slug',
        name: 'tag',
        component: require('./screens/posts/PostIndex').default,
    },
    {
        path: '/topic/:slug',
        name: 'topic',
        component: require('./screens/posts/PostIndex').default,
    },
    {
        path: '/:username',
        name: 'user',
        component: require('./screens/users/UserShow').default,
    },
    {
        path: '/:username/:slug',
        name: 'post',
        component: require('./screens/posts/PostShow').default,
    },
    {
        path: '*',
        name: 'catch-all',
        redirect: '/',
    },
]

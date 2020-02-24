export default [
    {
        path: '/studio',
        name: 'home',
        component: require('./screens/posts/PostIndex').default,
    },
    {
        path: '/studio/tag/:slug',
        name: 'tag',
        component: require('./screens/tags/PostIndex').default,
    },
    {
        path: '/studio/topic/:slug',
        name: 'topic',
        component: require('./screens/topics/PostIndex').default,
    },
    {
        path: '/studio/@:username',
        name: 'user',
        component: require('./screens/users/UserShow').default,
    },
    {
        path: '/studio/@:username/:slug',
        name: 'post',
        component: require('./screens/posts/PostShow').default,
    },
    {
        path: '*',
        name: 'catch-all',
        redirect: '/studio',
    },
]

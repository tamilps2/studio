export default [
    {
        path: '/studio',
        name: 'home',
        component: require('./screens/HomeScreen').default,
    },
    {
        path: '/studio/tag/:slug',
        name: 'tag',
        component: require('./screens/TagScreen').default,
    },
    {
        path: '/studio/topic/:slug',
        name: 'topic',
        component: require('./screens/TopicScreen').default,
    },
    {
        path: '/studio/:identifier',
        name: 'user',
        component: require('./screens/UserScreen').default,
    },
    {
        path: '/studio/:identifier/:slug',
        name: 'post',
        component: require('./screens/PostScreen').default,
    },
    {
        path: '*',
        name: 'catch-all',
        redirect: '/studio',
    },
]

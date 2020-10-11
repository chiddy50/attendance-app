const { default: VueRouter } = require('vue-router');
Vue.use(VueRouter);

const routes = [
    { 
        path: '/login', 
        name: 'login',
        component: require('./components/Login.vue').default
    },
    { 
        path: '/register', 
        name: 'register',
        component: require('./components/Register.vue').default
    },
    { 
        path: '/add-staff', 
        name: 'addStaff',
        component: require('./views/AddStaff.vue').default,
        meta: { requiresAuth: true }
    },
    { 
        path: '/staff/:id', 
        name: 'staffRecord',
        component: require('./views/StaffRecord.vue').default,
        meta: { requiresAuth: true }
    },
    // { 
    //     path: '/articles', 
    //     name: 'articles',
    //     component: require('./components/Articles.vue').default,
    //     meta: { requiresAuth: true }
    // },
    { 
        path: '/', 
        name: 'dashboard',
        component: require('./views/Dashboard.vue').default,
        // meta: { requiresAuth: true }
    },
    { 
        path: '/teams', 
        name: 'team',
        component: require('./views/Team.vue').default,
        meta: { requiresAuth: true }
    },
    { 
        path: '/roles', 
        name: 'roles',
        component: require('./views/Role.vue').default
        // meta: { requiresAuth: true }
    },
    { 
        path: '/attendance', 
        name: 'attendance',
        component: require('./views/Attendance.vue').default,
        meta: { requiresAuth: true }
    },
    { 
        path: '/check-records', 
        name: 'checkRecords',
        component: require('./views/CheckRecords.vue').default,
        meta: { requiresAuth: true }
    },
    { 
        path: '/month', 
        name: 'month',
        component: require('./views/Month.vue').default,
        meta: { requiresAuth: true }
    },
    
    
];

const router = new VueRouter({
    routes: routes,
    // mode: "history"
})

// export default router;
module.exports = router
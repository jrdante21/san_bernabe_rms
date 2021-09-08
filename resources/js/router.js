import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

import XNotFound from './components/XNotFound.vue'

import ResForm from './components/ResidentForm.vue'
import Home from './components/Home.vue'

import ResidentsInfo from './components/ResidentsInfo.vue'
import ResidentsHome from './components/ResidentsHome.vue'
import Docs from './components/Docs.vue'

const router = createRouter({
    history: createWebHistory(),
    linkActiveClass: 'active',
    routes: [
        { 
            path: '/admin/:pathMatch(.*)*', 
            name: 'NotFound', 
            component: XNotFound 
        },{
            path: '/admin/',
            alias: '/admin/home',
            component: Home,
        },{
            path: '/admin/residents',
            name: 'Residents',
            component: ResidentsHome,
            children:[
                {
                    path: '',
                    component: () => import(/* webpackChunkName: "residents" */ './components/ResidentsList.vue'),
                },{
                    path: ':id',
                    component: ResidentsInfo,
                },{
                    path: ':id/edit',
                    component: ResForm,
                },{
                    path: 'add',
                    component: ResForm
                }
            ]
        },{
            path: '/admin/transactions/:typeName',
            name: 'Documents',
            component: Docs,
            props: true,
        },{
            path: '/admin/officials',
            name: 'Officials',
            component: () => import(/* webpackChunkName: "officials" */ './components/Officials.vue'),
        },{
            path: '/admin/administrators',
            name: 'Administrators',
            component: () => import(/* webpackChunkName: "admins" */ './components/Admins.vue'),
        }
    ],
})

export default router
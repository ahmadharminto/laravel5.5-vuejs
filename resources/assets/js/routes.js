import VueRouter from 'vue-router';
import Task from './components/Task.vue';
import Profile from './components/Profile.vue';
import Chat from './components/Chat.vue';

let routes = [
    {
        path: '/task',
        name: 'task',
        component: Task
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile
    },
    {
        path: '/chat',
        name: 'chat',
        component: Chat
    }
];


export default new VueRouter({
    mode: 'history',
    routes
});
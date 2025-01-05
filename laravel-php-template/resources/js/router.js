import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("./views/MainView.vue"),
    },
    {
        path: "/test",
        component: () => import("./views/Test.vue"),
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
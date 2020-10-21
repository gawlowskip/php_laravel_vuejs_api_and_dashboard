<template>
    <div>
        <navigation></navigation>
        <div class="page-title" v-if="title">
            <div class="container">
                <h1>{{ title }}</h1>
            </div>
        </div>
        <main>
            <transition name="slide-fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </main>
    </div>
</template>

<script>
    import Navigation from "../Navigation";
    export default {
        name: "Admin",
        components: {
            Navigation
        },
        props: {

        },
        watch: {
            '$route' (routeTo) {
                this.title = this.trans.get(routeTo.meta.title)
                document.title = `Dashboard - ${this.title}` || 'Dashboard'
            }
        },
        data() {
            return {
                title: ''
            }
        },
        methods: {

        },
        created() {

        }
    }
</script>

<style scoped>
    .slide-fade-enter-active {
        transition: all .2s ease;
    }
    .slide-fade-leave-active {
        transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0;
    }
</style>
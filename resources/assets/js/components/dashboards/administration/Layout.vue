<style lang="scss" scoped>
    div.content {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        padding-top: 2em;
        padding-bottom: 1em;
        overflow: auto;
        transition: left .5s;
    }

    a.btn-sidebar-alter {
        position: fixed;
        left: 1em; top: 1em;
        z-index: 11;
        color: white;
        cursor: pointer;
        &:hover { color: white }
    }
</style>

<template>
    <div style="height: 100%">

        <notifications-panel></notifications-panel>

        <a class="btn-sidebar-alter" @click="sidebar_opened = !sidebar_opened">
            <i class="fas fa-bars centered fa-2x"></i>
        </a>

        <transition name="sidebar">
            <sidebar v-show="sidebar_opened"/>
        </transition>

        <div class="content" :style="(loading.status ? 'overflow: hidden; ' : '') + (sidebar_opened ? 'left: 15vw': 'left: 0vw')" ref="contentDiv">
            <loading-cover v-if="loading.status" :message="loading.message"/>
            <div class="container-fluid">
                <div class="row">
                    <div class="offset-1 col-10">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    components: {
        'sidebar': require('./partials/Sidebar.vue'),
        'notifications-panel': require('./partials/NotificationsPanel'),
    },
    data() {
        return {
            loading: {
                status: false,
                message: ''
            },
            sidebar_opened: true
        };
    },
    mounted() {
        this.$on('loading-status', loading => {
            this.$refs.contentDiv.scrollTo(0,0);
            this.loading = loading;
        });
        this.$on('fatal-error', error => console.log("Fatal error: ", error));
    }
}
</script>

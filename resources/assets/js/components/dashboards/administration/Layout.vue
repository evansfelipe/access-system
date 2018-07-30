<style lang="scss" scoped>
    div.content {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        padding-top: 2em;
        padding-bottom: 1em;
        overflow: auto;
        transition: left .5s;
    }
    div.notifications {
        position: absolute;
        z-index: 2;
        right: 1em;
        top: 1em;
        & .alert {
            width: 20vw;
            & > i {
                position: absolute;
                right: 0;
                top: 0;
                padding: 1em .8em;
                &:hover { cursor: pointer }
            }
        }
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

        <div class="notifications">
            <transition-group name="notifications">
                <div v-for="notification in notifications" :key="notification.id" :class="'alert alert-' + notification.type">
                    <i class="fas fa-times" @click="closeNotification(notification)"></i>
                    {{ notification.text }}
                </div>
            </transition-group>
        </div>

        <a class="btn-sidebar-alter" @click="sidebar_opened = !sidebar_opened">
            <i class="fas fa-bars centered fa-2x"></i>
        </a>

        <transition name="sidebar">
            <sidebar v-show="sidebar_opened"/>
        </transition>

        <div class="content" :style="(loading.status ? 'overflow: hidden; ' : '') + (sidebar_opened ? 'left: 15vw': 'left: 0vw')" ref="contentDiv">
            <loading-cover v-if="loading.status" :message="loading.message"/>
            <div class="container">
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
    },
    data() {
        return {
            loading: {
                status: false,
                message: ''
            },
            next_notification: 0,
            notifications: [],
            sidebar_opened: true
        };
    },
    mounted() {
        this.$on('loading-status', loading => {
            this.$refs.contentDiv.scrollTo(0,0);
            this.loading = loading;
        });
        this.$on('fatal-error', error => console.log("Fatal error: ", error));
        this.$on('new-notification', notification => { 
            this.notifications.push({
                id: this.next_notification++,
                ...notification
            });
        });
    },
    methods: {
        closeNotification: function(notification) {
            let index = this.notifications.indexOf(notification);
            if(index !== -1) {
                this.notifications.splice(index, 1);
            }
        }
    }
}
</script>

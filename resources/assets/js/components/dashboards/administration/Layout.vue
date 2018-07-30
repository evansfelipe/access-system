<style lang="scss" scoped>
    div.content {
        position: absolute;
        left: 15vw;
        right: 0;
        top: 0;
        bottom: 0;
        padding-top: 2em;
        padding-bottom: 1em;
        overflow: auto;
    }
</style>

<template>
    <div style="height: 100%">
        <sidebar/>
        <div class="content" :style="loading.status ? 'overflow: hidden' : ''" ref="contentDiv">
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
            notification: []
        };
    },
    mounted() {
        this.$on('loading-status', loading => {
            this.$refs.contentDiv.scrollTo(0,0);
            this.loading = loading
        });
        this.$on('fatal-error', error => console.log("Fatal error: ", error));
        this.$on('new-notification', notification => this.notification.push(notification));
    }
}
</script>

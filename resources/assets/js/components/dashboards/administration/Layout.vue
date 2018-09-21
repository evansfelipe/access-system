<style lang="scss" scoped>
    div.content {
        position: absolute;
        right: 0; top: 0; bottom: 0;
        left: 250px;
        &.expanded { left: 0vw }
        padding-top: 1em;
        padding-bottom: 1em;
        overflow: auto;
        transition: left .5s;
    }
</style>

<template>
    <div>
        <notifications-panel/>

        <sidebar v-show="sidebar_opened"/>

        <div :class="'content ' + (!sidebar_opened ? 'expanded' : '')" :style="(loading.state ? 'overflow: hidden; ' : '')" ref="contentDiv">
            <loading-cover v-if="loading.state" :message="loading.message"/>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 offset-lg-1 col-lg-10">
                        <router-view/>
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
    computed: {
        loading: function() {
            return {
                state: this.$store.getters.loading.state,
                message: this.$store.getters.loading.message
            };
        },
        sidebar_opened: {
            get: function() {
                return this.$store.getters.sidebar_opened;
            },
            set: function(newVal) {
                this.$store.commit('toggleSidebar', newVal);
            }
        }
    },
    mounted() {
        document.addEventListener('navbar-button-clicked', () => this.sidebar_opened = !this.sidebar_opened);
    },
    watch: {
        'loading.state': function() {
            this.$refs.contentDiv.scrollTo(0,0);
        }
    }
}
</script>

<style lang="scss" scoped>
    div.notifications {
        position: absolute;
        z-index: 1000;
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

    .notifications-enter-active { transition: all .3s ease }
    .notifications-leave-active { transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0) }
    .notifications-enter, .notifications-leave-to { transform: translateX(10px); opacity: 0; }
</style>

<template>
    <div class="notifications">
        <transition-group name="notifications">
            <div v-for="notification in this.$store.getters.notifications"
                 :key="notification.id" :class="`alert alert-${notification.type}`"
            >
                <i class="fas fa-times" @click="closeNotification(notification)"></i>
                {{ notification.message }}
            </div>
        </transition-group>
    </div>
</template>

<script>
export default {
    methods: {
        closeNotification: function(notification) {
            this.$store.commit('removeNotification', notification.id);
        }
    }
}
</script>

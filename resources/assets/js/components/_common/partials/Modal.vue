<style lang="scss" scoped>
    div.modal-wrapper {
        // Display
        display: flex;
        align-items: center; justify-content: center;
        // Position
        position: fixed;
        top: 0; right: 0; bottom: 0; left: 0;
        z-index: 15;
        // Color
        background-color: rgba(0, 0, 0, .2);
    }
    // Open/close transitions.
    .slide-leave-active, .slide-enter-active {
        transition: all .5s;
    }
    .slide-enter, .slide-leave-to {
        opacity: 0;
        transform: translateY(100%);
    }
</style>

<template>
    <transition name="slide">
        <div v-if="visible" class="modal-wrapper">
            <div class="card card-default shadow">
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col text-right">
                            <a class="btn btn-link cursor-pointer p-0" title="Cerrar" @click="close"><i class="fas fa-times fa-sm"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <slot/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        visible: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    methods: {
        close: function() {
            this.$emit('closed');
        },
    }
}
</script>


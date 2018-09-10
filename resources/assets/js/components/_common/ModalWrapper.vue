<style lang="scss" scoped>
    div.modal.show {
        display: block;
        background-color: rgba(0, 0, 0, .5);
        overflow-y: auto;
    }

    button.close {
        outline: none;
    }

    .modal-enter { opacity: 0 }
    .modal-leave-active { opacity: 0 }
    .modal-enter .modal-container,
    .modal-leave-active .modal-container { transform: scale(1.1) }
</style>


<template>
    <transition name="modal">
        <div v-if="visible" class="modal show fade" :id="id" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header pb-0" style="border-bottom: 0">
                        <h5 class="modal-title">{{ title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot/>
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
            type:     Boolean,
            required: false,
            default:  false
        },
        title: {
            type:     String,
            required: false,
            default:  ""
        },
    },
    computed: {
        id: function() { return this._uid }
    },
    methods: {
        close: function() { this.$emit('closed') },
    }
}
</script>


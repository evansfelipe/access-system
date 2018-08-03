<style lang="scss" scoped>
    .confirmation-modal-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        z-index: 15;
        top: 0; bottom: 0; left: 0; right: 0;
        background-color: rgba(0, 0, 0, .7);
        & > .confirmation-modal {
            min-width: 30vw;
            background-color: white;
            padding: 1.5em;
            border-radius: 5px;
            & > .content {
                & > .buttons {
                    text-align: right;
                    margin-top: 15px;
                    & > button + button {
                        margin-left: 5px;
                    }
                }
            }
        }
    }

    .confirmation-modal-enter, .confirmation-modal-leave-to { transform: translateY(-100vh) }
    .confirmation-modal-enter-active, .confirmation-modal-leave-active  { transition: all .3s }
</style>

<template>
    <div style="display: inline">
        <button type="button" :class="btnclass" @click="open"><slot/></button>
        <transition name="confirmation-modal">
            <div v-if="opened" class="confirmation-modal-wrapper">
                <div class="confirmation-modal">
                    <div class="content">
                        ¿Está seguro que desea realizar esta acción?
                        <div class="buttons">
                            <button type="button" class="btn btn-sm btn-danger" @click="cancel">Cancelar</button>
                            <button type="button" class="btn btn-sm btn-success" @click="confirm">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>    
</template>

<script>
export default {
    props: {
        btnclass: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        return {
            opened: false
        };
    },
    methods: {
        open: function() {
            this.opened = true;
        },
        confirm: function() {
            this.$emit('confirmed');
            this.opened = false;
        },
        cancel: function() {
            this.opened = false;
        }
    }
}
</script>

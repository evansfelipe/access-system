<style lang="scss" scoped>
    button.btn-link {
        text-decoration: none;
        font-weight: bold;
        padding: 5px;
        &.btn-cancel { 
            color: red !important; 
            &:hover {
                color: white !important;
                background-color: red;
            }
        }
        &.btn-confirm {
            color: #3F729B !important;
            &:hover {
                color: white !important;
                background-color: #3F729B;
            }
        }
    }
</style>

<template>
    <div style="display: inline">
        <button type="button" :class="btnclass" @click="open"><slot/></button>
        <modal-wrapper :visible="opened" @closed="cancel">
            <h6 class="text-center">
                ¿Está seguro que desea realizar esta acción?
            </h6>
            <div class="text-right mt-2">
                <button type="button" class="btn btn-sm btn-link btn-cancel" @click="cancel">Regresar</button>
                <button type="button" class="btn btn-sm btn-link btn-confirm" @click="confirm">Confirmar</button>
            </div>
        </modal-wrapper>
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

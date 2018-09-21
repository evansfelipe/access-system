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
    <button type="button" :class="btnClass" @click="open"><slot/></button>
</template>

<script>
export default {
    props: {
        btnClass: {
            type: String,
            required: false,
            default: ''
        }
    },
    methods: {
        open: function() {
            this.$confirm('¿Está seguro que desea realizar esta acción?', '', {
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                type: 'error'
            })
            .then(() => this.$emit('confirmed'))
            .catch(() => {});
        },
    }
}
</script>

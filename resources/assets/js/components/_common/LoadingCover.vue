<style lang="scss" scoped>
    div.loading-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background-color: white;
        z-index: 2000;
        border-radius: 5px;
    }
</style>

<template>
    <div class="loading-panel">
        <i class="fas fa-spinner fa-spin fa-4x"></i>
        <br>
        <h4>{{ message }}</h4>
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type:     String,
            required: false,
            default:  'Cargando...'
        }
    }
}
</script>

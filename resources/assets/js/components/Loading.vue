<style scoped>
    div.loading-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        background-color: white;
        opacity: .7;
        color: black;
        z-index: 2;
        border-radius: 5px;
    }
</style>


<template>
    <div class="loading-panel">
        <i class="fas fa-spinner fa-spin fa-4x"></i>
    </div>
</template>
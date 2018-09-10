<style lang="scss" scoped>
    div.wrapper {
        width: 100%;
        & > span {
            position: absolute;
            left: 2rem; top: 4px;
            background-color: white;
            padding: 0 5px;
        }
    }
</style>


<template>
    <div class="wrapper">
        <span>
            <slot/>
        </span>
        <hr>
    </div>
</template>


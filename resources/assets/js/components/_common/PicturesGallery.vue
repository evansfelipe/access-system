<style lang="scss" scoped>
    div.img-wrapper {
        position: relative;
        display: inline-block;
        align-items: center;
        cursor: pointer;

        &.thumbnail {
            height: 25%;
            width: 25%;
            padding: 1px;
        }

        & > img {
            width: 100%;
            height: 100%;
        }

        & > div.hover { display: none }

        &:hover > div.hover {
            background-color: rgba(0, 0, 0, .5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
        }
    }
</style>

<template>
    <div style="min-height: 150px">
        <div v-show="!current" class="img-wrapper thumbnail" v-for="(picture, key) in links" :key="key" @click="current = picture">
            <img :src="picture">
            <div class="hover"><i class="fas fa-search-plus fa-2x"></i></div>
        </div>
        <div v-if="current !== null" class="img-wrapper" @click="current = null">
            <img :src="current">
            <div class="hover"><i class="fas fa-search-minus fa-2x"></i></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        links: {
            type:     Array,
            required: true
        }
    },
    data() {
        return {
            current: null,
        };
    }
}
</script>

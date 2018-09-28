<style lang="scss" scoped>
    div.card-body {
        max-height: 70vh;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>


<template>
    <div class="card">
        <div class="card-header" :id="`c-${_uid}`">
            <h6 class="mb-0 cursor-pointer" data-toggle="collapse" :data-target="`#${_uid}`" aria-expanded="true" :aria-controls="`#${_uid}`">
                {{ title }}
            </h6>
        </div>
        <div :id="_uid" :class="`collapse ${active ? 'show' : ''}`" :aria-labelledby="`c-${_uid}`" :data-parent="`#${accordionId}`">
            <div class="card-body">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        accordionId: {
            required: true
        },
        title: {
            type: String,
            required: true
        },
        active: {
            type: Boolean,
            required: false,
            default: false
        }
    }
}
</script>

<style lang="scss" scoped>
    li.nav-item {
        & + &   { margin-left: 2px }
        &:hover { 
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.06);
            border-bottom-color: transparent;
        }
        & > a.nav-link {
            color: grey;
            background-color: rgba(0, 0, 0, 0.02);
            border-color: rgba(0, 0, 0, 0.03);
            border-bottom-color: transparent;
            &.active, &.active:hover {
                color: black;
                background-color: white;
                border-color: rgb(222,222,222);
                border-bottom-color: white;
            }
        }
    }
</style>

<template>
    <li class="nav-item">
        <a :class="'nav-link ' + (active ? 'active' : '')">
            <i v-if="hasErrors === null" :class="icon"></i>
            <i v-else :class="'far fa-' + (hasErrors ? 'check-circle text-success':'times-circle text-danger')"></i>
            <slot/>
        </a>
    </li>
</template>

<script>
export default {
    props: {
        active: {
            type: Boolean,
            required: false,
            default:  false
        },
        hasErrors: {
            required: false,
            default:  null
        },
        icon: {
            type: String,
            required: false,
            default: ''
        }
    }
}
</script>

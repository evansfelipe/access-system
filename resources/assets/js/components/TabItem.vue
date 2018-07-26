<style lang="scss" scoped>
    li.nav-item {
        & + &   { margin-left: 1px }
        &:hover { cursor: pointer  }
        & > a.nav-link {
            color: grey;
            &.active {
                color: black;
                background-color: white;
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
            required: false
        }
    }
}
</script>

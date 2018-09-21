<style lang="scss" scoped>
    .collapse-enter-active, .collapse-leave-active { transition: all .3s }
    .collapse-enter, .collapse-leave-to { opacity: 0; max-height: 0; }
    .collapse-enter-to, .collapse-leave { opacity: 1; max-height: 100vh; }
</style>

<template>
    <div>
        <div v-if="!updating" class="row text-right">
            <div class="col">
                <button class="btn btn-link btn-sm" @click="advancedSearch">
                    <i :class="`fas fa-angle-${advanced_search ? 'up' : 'down'}`"></i>
                    {{ advanced_search ? 'Ocultar' : 'Mostrar' }} búsqueda avanzada
                </button>
            </div>
        </div>
        <transition name="collapse">
            <div v-show="advanced_search" class="card">
                <div class="card-body" style="background: #fafafa">
                    <div class="form-row">
                        <slot name="advanced-search-filters"/>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body" :style="updating ? 'min-height: 60vh' : ''">
                <loading-cover v-if="updating"/>
                <slot v-else name="main-content"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        updating: {
            type:       Boolean,
            required:   false,
            default:    false
        }
    },
    data() {
        return {
            advanced_search: false
        };
    },
    methods: {
        advancedSearch: function() {
            this.advanced_search = !this.advanced_search;
            this.$emit('advanced-search');
        }
    }
}
</script>

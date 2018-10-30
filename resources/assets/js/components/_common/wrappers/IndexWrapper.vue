<style lang="scss" scoped>
    .collapse-enter-active, .collapse-leave-active { transition: all .3s }
    .collapse-enter, .collapse-leave-to { opacity: 0; max-height: 0; }
    .collapse-enter-to, .collapse-leave { opacity: 1; max-height: 100vh; }
</style>

<template>
    <div>
        <div class="row text-right">
            <div class="col">
                <button class="btn btn-link btn-sm" @click="toggle">
                    <i :class="`fas fa-angle-${advanced_search.opened ? 'up' : 'down'}`"></i>
                    {{ advanced_search.opened ? 'Ocultar' : 'Mostrar' }} búsqueda
                </button>
            </div>
        </div>
        <transition name="collapse">
            <div v-if="advanced_search.opened" class="card">
                <div class="card-body advanced-search-slot" style="background: #fafafa">
                    <slot name="advanced-search-filters"/>

                    <div class="form-row mt-3">
                        <div class="col-4">
                            <small v-if="advanced_search.sended">
                                <i class="fas fa-exclamation-circle"></i>
                                Hay filtros aplicados
                            </small>
                        </div>
                        <div class="col-8 text-right">
                            <el-tooltip class="item" effect="dark" content="Limpiar filtros" placement="left">
                                <button class="btn btn-link btn-sm mr-4" @click="clear">
                                    <i class="ml-1 fas fa-eraser"></i>
                                </button>
                            </el-tooltip>
                            <button class="btn btn-outline-unique btn-sm" @click="submit">
                                <i class="mr-1 fas fa-search"></i>
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body">
                <slot name="main-content"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            advanced_search: {
                opened:  false,
                sended:  false
            },
        };
    },
    methods: {
        toggle: function() {
            this.advanced_search.opened = !this.advanced_search.opened;
            if(!this.advanced_search.opened) {
                this.clear();
            }
        },
        clear: function() {
            this.advanced_search.sended = false;
            this.$emit('advanced-search-clear');
        },
        submit: function() {
            this.advanced_search.sended = true;
            this.$emit('advanced-search-submit');
        }
    }
}
</script>

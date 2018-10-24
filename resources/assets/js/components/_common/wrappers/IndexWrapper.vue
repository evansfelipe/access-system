<style lang="scss" scoped>
    .collapse-enter-active, .collapse-leave-active { transition: all .3s }
    .collapse-enter, .collapse-leave-to { opacity: 0; max-height: 0; }
    .collapse-enter-to, .collapse-leave { opacity: 1; max-height: 100vh; }

    .updating-enter-active, .updating-leave-active { transition: all .3s }
    .updating-enter, .updating-leave-to { opacity: 0; max-height: 0; }
    .updating-enter-to, .updating-leave { opacity: 1; max-height: 100vh; }

    div.updating {
        background-color: #3F729B;
        color: white;
        text-align: center;
        font-weight: bold;
        position: absolute;
        padding: .5em 0;
        top: 0;
        left: calc(50% - 75px);
        z-index: 100;
        width: 150px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
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
            <div v-show="advanced_search.opened" class="card">
                <div class="card-body advanced-search-slot" style="background: #fafafa">
                    <slot name="advanced-search-filters"/>

                    <div class="form-row mt-3">
                        <div class="col-12 text-right">
                            <el-tooltip class="item" effect="dark" content="Limpiar filtros" placement="left">
                                <button :disabled="updating" class="btn btn-link btn-sm mr-4" @click="clear">
                                    <i class="ml-1 fas fa-eraser"></i>
                                </button>
                            </el-tooltip>
                            <button :disabled="updating" class="btn btn-outline-unique btn-sm" @click="submit">
                                <i :class="`mr-1 fas fa-${updating ? 'circle-notch fa-spin' : 'search'}`"></i>
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <div class="card">
            <div class="card-body">
                <transition name="updating">
                    <div v-if="updating" class="updating shadow-sm">
                        Cargando...
                    </div>
                </transition>
                <slot name="main-content"/>
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
            advanced_search: {
                opened:  false,
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
            this.$emit('advanced-search-clear');
        },
        submit: function() {
            this.$emit('advanced-search-submit');
        }
    }
}
</script>

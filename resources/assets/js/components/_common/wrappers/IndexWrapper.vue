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
                    <i :class="`fas fa-angle-${advanced_search.opened ? 'up' : 'down'}`"></i>
                    {{ advanced_search.opened ? 'Ocultar' : 'Mostrar' }} búsqueda avanzada
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
                                <button class="btn btn-link btn-sm mr-4"  @click="$emit('advanced-search-clear')">
                                    <i class="ml-1 fas fa-eraser"></i>
                                </button>
                            </el-tooltip>
                            <button :disabled="advanced_search.loading" class="btn btn-outline-unique btn-sm" @click="submitAdvancedSearch">
                                <i :class="`mr-1 fas fa-${advanced_search.loading ? 'circle-notch fa-spin' : 'search'}`"></i>
                                Buscar
                            </button>
                        </div>
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
        },
        advancedSearchRoute: {
            type:       String,
            required:   true
        },
        conditions: {
            type:       Object,
            required:   false,
            default:    () => {}
        }
    },
    data() {
        return {
            advanced_search: {
                opened:  false,
                loading: false,
                ids: null
            },
        };
    },
    methods: {
        advancedSearch: function() {
            this.advanced_search.opened = !this.advanced_search.opened;
            if(!this.advanced_search.opened) {
                this.$emit('advanced-search-clear');
            }
        },
        submitAdvancedSearch: function() {
            this.advanced_search.loading = true;
            axios.get(this.advancedSearchRoute, {params: this.conditions})
            .then(response  => this.$emit('advanced-search-success', response.data))
            .catch(error    => {
                this.$store.dispatch('addNotification', {type: 'danger', message: 'Algo salió mal. Intentelo nuevamente.'});
                this.$emit('advanced-search-clear')
            })
            .finally(()     => this.advanced_search.loading = false)
        },
    }
}
</script>

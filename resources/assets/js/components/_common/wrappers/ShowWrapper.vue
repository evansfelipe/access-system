<style lang="scss" scoped>
    .el-button + .el-button {
        margin-left: 0;
        margin-top: 10px;
    }

    div.card-loading > div.card-body > div.row { height: 40vh }

    .wrapper {
        text-align: right;
        & > .item {
            display: block;
            float:right; clear:right;
        }
    }
    
    button.toggle-enabled {
        background-color: transparent;
        border: 0;
        border-radius: 3px;
        outline: none;
        margin: 0;
        min-width: 100px;
        color: white;
        cursor: pointer;
        font-weight: bold;
        font-size: 80%;
        &.enabled {
            &::after { content: 'Habilitado' }
            background-color: #00C851;
            &:hover {
                background-color: #ff4444;
                &::after { content: 'Deshabilitar' }
            }
        }

        &.disabled {
            background-color: #ff4444;
            &::after { content: 'Deshabilitado' }
            &:hover {
                background-color: #00C851;
                &::after { content: 'Habilitar' }
            }
        }
    }
</style>

<template>
    <div>
        <div v-if="!loading && enabled !== null" class="text-right" @mouseover="hover = !hover" @mouseleave="hover = !hover">
            <button :class="`toggle-enabled ${enabled ? 'enabled' : 'disabled'}`" @click="toggleEnabled">
            </button>
        </div>

        <slot name="tabs"/>
        <div :class="`card card-default borderless-top ${loading ? 'card-loading' : ''}`">
            <div class="card-body">
                <div class="row">
                    <div class="col-11">
                        <loading-cover v-if="loading"/>
                        <slot v-else/>
                    </div>
                    <div class="wrapper col-1">

                        <el-tooltip class="item" effect="dark" content="Editar" placement="left">
                            <el-button @click="$emit('edit')" icon="el-icon-edit" circle plain/>
                        </el-tooltip>
                        
                        <el-tooltip class="item" effect="dark" content="Exportar como PDF" placement="left">
                            <el-button @click="$emit('pdf')" icon="el-icon-document" circle plain/>
                        </el-tooltip>

                        <slot name="extra-buttons"/>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        loading: {
            type:     Boolean,
            required: false,
            default:  false
        },
        enabled: {
            type:     Boolean,
            required: false,
            default:  null
        }
    },
    data() {
        return {
            hover: false
        };
    },
    methods: {
        toggleEnabled: function() {
            this.$confirm('¿Está seguro?', '', {
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
                type: 'error'
            })
            .then(() => this.$emit('toggle-enabled'))
            .catch(() => {});
        },
    }
}
</script>

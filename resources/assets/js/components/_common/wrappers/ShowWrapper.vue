<style lang="scss" scoped>
    .el-button + .el-button {
        margin-left: 0;
        margin-top: 10px;
    }

    div.card-loading > div.card-body > div.row { height: 40vh }
</style>

<template>
    <div :class="`card card-default borderless-top ${loading ? 'card-loading' : ''}`">
        <div class="card-body">
            <div class="row">
                <div class="col-11">
                    <loading-cover v-if="loading"/>
                    <slot v-else/>
                </div>
                <div class="col-1 text-center">
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
</template>

<script>
export default {
    props: {
        loading: {
            type:     Boolean,
            required: false,
            default:  false
        }
    },
}
</script>

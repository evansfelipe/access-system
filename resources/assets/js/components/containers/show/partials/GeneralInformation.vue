<template>
    <div>
        <div class="row">
            <div class="col-12">
                <hr-label>Información general</hr-label>
            </div>
            <div class="col-2">
                <small>Número de serie</small><br>
                <strong>{{ values.series_number }}</strong>
            </div>
            <div class="col-2">
                <small>Formato</small><br>
                <strong>{{ values.format }}</strong>
            </div>
            <div class="col-2">
                <small>Tamaño</small><br>
                <strong>{{ values.size }}</strong>
            </div>
            <div class="col-2">
                <small>Marca</small><br>
                <strong>{{ values.brand }}</strong>
            </div>
            <div class="col-2">
                <small>Modelo</small><br>
                <strong>{{ values.model }}</strong>
            </div>
            <div class="col-2">
                <small>Color</small><br>
                <strong>{{ values.colour }}</strong>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <hr-label>Camiones asignados</hr-label>
            </div>
            <div class="col-12">
                <custom-table
                    :columns="columns"
                    :rows="values.assigned_trucks"
                    :rowsquantity="5"
                    :no-rows-message="'No hay vehículos asignadas'"
                    @rowclicked="({id}) => $router.push(`/vehicles/show/${id}`)"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            columns: [
                {name: 'plate',         text: 'Patente'},
                {name: 'brand',         text: 'Marca'},
                {name: 'model',         text: 'Modelo'},
                {name: 'company_name',  text: 'Empresa'}
            ]
        };
    }
}
</script>

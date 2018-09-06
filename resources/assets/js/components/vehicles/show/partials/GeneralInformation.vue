<template>
    <div>
        <div class="row">
            <div class="col-12">
                <hr-label>Información general</hr-label>
            </div>
            <div class="col-2">
                <small>Tipo</small><br>
                <strong>{{ values.type }}</strong>
            </div>
            <div class="col-2">
                <small>Patente</small><br>
                <strong>{{ values.plate }}</strong>
            </div>
            <div class="col-2">
                <small>Año</small><br>
                <strong>{{ values.year }}</strong>
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
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <hr-label>Dueños del vehículo</hr-label>
                    </div>
                    <div class="col-6">
                        <small>Empresa</small><br>
                        <strong class="text-link" title="Ver información de la empresa" @click="$router.push(`/companies/show/${values.company.id}`)">
                            {{ values.company.name }}
                        </strong>
                    </div>
                    <div class="col-6">
                        <small>Titular</small><br>
                        <strong>{{ values.owner }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <hr-label>Vencimientos</hr-label>
                    </div>
                    <div class="col-6">
                        <small>Seguro</small><br>
                        <strong>{{ values.insurance }}</strong>
                    </div>
                    <div class="col-6">
                        <small>VTV</small><br>
                        <strong>{{ values.vtv }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <hr-label>Personas asignadas</hr-label>
            </div>
            <div class="col-12">
                <custom-table
                    :columns="columns"
                    :rows="values.assigned_people"
                    :rowsquantity="5"
                    @rowclicked="({id}) => $router.push(`/people/show/${id}`)"
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
                {name: 'full_name',     text: 'Apellido y Nombre'},
                {name: 'cuil',          text: 'CUIL / CUIT'},
                {name: 'company_name',  text: 'Empresas'}
            ]
        };
    }
}
</script>

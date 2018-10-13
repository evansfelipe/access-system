<template>
    <div>
        <div class="row">
            <div class="col-12">
                <hr-label>Información general</hr-label>
            </div>
            <div class="col-3">
                <small>Nombre</small><br>
                <strong>{{ values.name }}</strong>
            </div>
            <div class="col-3">
                <small>Empresa</small><br>
                <strong>{{ values.company }}</strong>
            </div>
            <div class="col-3">
                <small>Entrada</small><br>
                <strong>{{ values.gate }}</strong>
            </div>
            <div class="col-3">
                <small>Franja horaria</small><br>
                <strong>{{ values.range }}</strong>
            </div>
            <div class="col-12 mt-2">
                <small>Días habilitados</small>
            </div>
            <div class="col-12">
                <div v-for="(value, day) in values.days" :key="day" class="d-inline mr-5">
                    <i v-if="value == 0" class="far fa-circle"></i>
                    <i v-else class="fas fa-check-circle"></i>
                    <strong>{{ day }}</strong>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <hr-label>Personas asignadas</hr-label>
            </div>
            <div class="col-12">
                <custom-table
                    :columns="columns"
                    :rows="[]"
                    :rowsquantity="5"
                    :no-rows-message="'No hay personas asignadas'"
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
                {name: 'last_name', text: 'Apellido'},
                {name: 'name',      text: 'Nombre'},
                {name: 'cuil',      text: 'CUIL / CUIT'}
            ]
        };
    }
}
</script>

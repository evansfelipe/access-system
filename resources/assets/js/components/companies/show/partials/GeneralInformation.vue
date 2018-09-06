<template>
    <div>
        <div class="row">
            <div class="col-4">
                <small>Razón social</small><br>
                <strong>{{ values.business_name }}</strong>
            </div>
            <div class="col-4">
                <small>Nombre</small><br>
                <strong>{{ values.name }}</strong>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-4">
                <small>Rubro</small><br>
                <strong>{{ values.area }}</strong>
            </div>
            <div class="col-4">
                <small>CUIT</small><br>
                <strong>{{ values.cuit }}</strong>
            </div>
            <div class="col-4">
                <small>Vencimiento</small><br>
                <strong>{{ values.expiration }}</strong>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <hr-label>Contacto</hr-label>
            </div>
            <div class="col-3">
                <small>Teléfono</small><br>
                <strong>{{ values.phone }}</strong>
            </div>
            <div class="col-3">
                <small>Fax</small><br>
                <strong>{{ values.fax }}</strong>
            </div>
            <div class="col-3">
                <small>Email</small><br>
                <strong>{{ values.email }}</strong>
            </div>
            <div class="col-3">
                <small>Página web</small><br>
                <strong>{{ values.web }}</strong>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <hr-label>Domicilio</hr-label>
            </div>
            <div class="col-2">
                <small>Dirección</small><br>
                <strong>{{ values.street }}</strong>
            </div>
            <div class="col-2">
                <small>Departamento</small><br>
                <strong>{{ values.apartment }}</strong>
            </div>
            <div class="col-2">
                <small>Código Postal</small><br>
                <strong>{{ values.cp }}</strong>
            </div>
            <div class="col-2">
                <small>Ciudad</small><br>
                <strong>{{ values.city }}</strong>
            </div>
            <div class="col-2">
                <small>Provincia</small><br>
                <strong>{{ values.province }}</strong>
            </div>
            <div class="col-2">
                <small>País</small><br>
                <strong>{{ values.country }}</strong>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        values: {
            type: Object,
            required: true,
        }
    }
}
</script>

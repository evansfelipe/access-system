<template>
    <div class="row d-flex align-items-center">
        <div class="col-md-4 text-center">
            <div class="offset-1 col-10">
                <img class="img-fluid rounded-circle" src="/pictures/no-image.jpg" alt="Subir imagen">                                                
            </div>
        </div>
        <div class="col-md-8">
            <h3>{{ person.full_name }}</h3>
            <br>
            <div class="row">
                <div class="col-4">
                    <small>Legajo</small>
                    <br>
                    <strong>{{ person.record }}</strong>
                </div>
                <div class="col-4">
                    <small>{{ person.document_type }}</small>
                    <br>
                    <strong>{{ person.document_number }}</strong>
                </div>
                <div class="col-4">
                    <small>CUIL / CUIT</small>
                    <br>
                    <strong>{{ person.cuil }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <small>Fecha de nacimiento</small>
                    <br>
                    <strong>{{ person.birthday }}</strong>
                </div>
                <div class="col-4">
                    <small>Nacionalidad</small>
                    <br>
                    <strong>{{ person.birth_country }}</strong>
                </div>
                <div class="col-4">
                    <small>Género</small>
                    <br>
                    <strong>{{ person.sex }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <small>Grupo y factor sanguíneo</small>
                    <br>
                    <strong>{{ person.blood_type }}</strong>
                </div>
                <div class="col-4">
                    <small>Prontuario PNA</small>
                    <br>
                    <strong>{{ person.pna }}</strong>
                </div>
                <div class="col-4">
                    <small>Email</small>
                    <br>
                    <strong>{{ person.email }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <small>Teléfono fijo</small>
                    <br>
                    <strong>{{ person.phone }}</strong>
                </div>
                <div class="col-4">
                    <small>Teléfono móvil</small>
                    <br>
                    <strong>{{ person.mobile_phone }}</strong>
                </div>
                <div class="col-4">
                    <small>FAX</small>
                    <br>
                    <strong>{{ person.fax }}</strong>
                </div>
            </div>
            <br>
            <!-- <div class="row">
                <div class="col-4">
                    <small>Dirección</small>
                    <br>
                    <strong>{{ person.address }}</strong><br>
                    <strong>{{ person.city }} ({{ person.cp }})</strong><br>
                    <strong>{{ person.province }}</strong><br>
                    <strong>{{ person.country }}</strong>
                </div>
            </div> -->
            <div class="row">
                <div class="col-4">
                    <small>Dirección</small>
                    <br>
                    <strong>{{ person.address }}</strong>
                </div>
                <div class="col-4">
                    <small>Departmaneto</small>
                    <br>
                    <strong>{{ person.apartment }}</strong>
                </div>
                <div class="col-4">
                    <small>Código Postal</small>
                    <br>
                    <strong>{{ person.cp }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <small>Ciudad</small>
                    <br>
                    <strong>{{ person.city }}</strong>
                </div>
                <div class="col-4">
                    <small>Provincia / Estado</small>
                    <br>
                    <strong>{{ person.province }}</strong>
                </div>
                <div class="col-4">
                    <small>País</small>
                    <br>
                    <strong>{{ person.country }}</strong>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        person: {
            required: true,
            type: Object
        }
    }
}
</script>



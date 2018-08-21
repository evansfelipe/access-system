<template>
    <div class="form-row">
        <div class="offset-1 col-10">
            <expiration-file label="Nota de la empresa"/>
            <expiration-file label="DNI"/>
            <expiration-file label="Número de prontuario"/>
            <expiration-file label="Registro de conducir"/>
            <expiration-file label="Certificado de cobertura ART"/>
            <expiration-file label="Certificado de cobertura Acc. Pers."/>
            <expiration-file label="Libreta de embarque"/>
            <expiration-file label="Cédula de embarque"/>
            <expiration-file label="Libreta sanitaria"/>
            <expiration-file label="Constancia de curso PBIP"/>
        </div>
    </div>
</template>

<script>
export default {
    components: {
            'expiration-file': require('./partials/ExpirationFile.vue')
        },
}
</script>

<template>
    <div class="form-row">
        <div class="offset-1 col-10">
            <expiration-file label="Nota de la empresa" name="company_note" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Documento de identidad" name="dni_copy" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Número de prontuario" name="pna_file" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Registro de conducir" name="driver_license" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Certificado de cobertura Acc. Pers." name="acc_pers" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Libreta de embarque" name="boarding_passbook" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Cédula de embarque" name="boarding_card" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Libreta sanitaria" name="health_notebook" :checked="false"
                @updated="data => update(data)"
            />
            <expiration-file label="Constancia de curso PBIP" name="pbip_file" :checked="false"
                @updated="data => update(data)"
            />
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'expiration-file': require('./partials/ExpirationFile.vue')
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.documentation.${name}`, value: value });
        }
    }
}
</script>

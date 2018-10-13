<template>
    <div class="form-row">
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Documento de identidad" :errors="errors['dni_copy']"
                                name="dni_copy" :value="documents['dni_copy']"
                                @expiration-changed="value => update({name: 'dni_copy.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'dni_copy.file', value: file}); update({name: 'dni_copy.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Número de prontuario" :errors="errors['pna_file']"
                                name="pna_file" :value="documents['pna_file']"
                                @expiration-changed="value => update({name: 'pna_file.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'pna_file.file', value: file}); update({name: 'pna_file.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Registro de conducir" :errors="errors['driver_license']"
                                name="driver_license" :value="documents['driver_license']"
                                @expiration-changed="value => update({name: 'driver_license.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'driver_license.file', value: file}); update({name: 'driver_license.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Constancia de curso PBIP" :errors="errors['pbip_file']"
                                name="pbip_file" :value="documents['pbip_file']"
                                @expiration-changed="value => update({name: 'pbip_file.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'pbip_file.file', value: file}); update({name: 'pbip_file.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Libreta de embarque" :errors="errors['boarding_passbook']"
                                name="boarding_passbook" :value="documents['boarding_passbook']"
                                @expiration-changed="value => update({name: 'boarding_passbook.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'boarding_passbook.file', value: file}); update({name: 'boarding_passbook.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Cédula de embarque" :errors="errors['boarding_card']"
                                name="boarding_card" :value="documents['boarding_card']"
                                @expiration-changed="value => update({name: 'boarding_card.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'boarding_card.file', value: file}); update({name: 'boarding_card.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Libreta sanitaria" :errors="errors['health_notebook']"
                                name="health_notebook" :value="documents['health_notebook']"
                                @expiration-changed="value => update({name: 'health_notebook.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'health_notebook.file', value: file}); update({name: 'health_notebook.name', value: name}); }"
            />
        </div>
        <div class="col-md-12 col-lg-6">
            <expiration-file    label="Certificado de cobertura Acc. Pers." :errors="errors['acc_pers']"
                                name="acc_pers" :value="documents['acc_pers']"
                                @expiration-changed="value => update({name: 'acc_pers.expiration', value})"
                                @file-changed="(file, name) => { update({name: 'acc_pers.file', value: file}); update({name: 'acc_pers.name', value: name}); }"
            />
        </div>
    </div>
</template>

<script>
export default {
    components: {
        'expiration-file': require('./ExpirationFile.vue'),
    },
    props: {
        documents: {
            type: Object,
            required: true
        },
        errors: {
            type: Array,
            required: true
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.documentation.documents.${name}`, value: value });
        }
    }
}
</script>

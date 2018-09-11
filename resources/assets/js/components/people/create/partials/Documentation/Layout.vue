<template>
    <div>
        <div v-for="job in jobs" :key="job.key" class="form-row">
            <div class="offset-1 col-10">
                <hr-label>{{job.company_name + ' | ' + job.activity_name}}</hr-label>
                <expiration-file label="Nota de la empresa" :name="`${job.key}-company_note`" :checked="false"
                    @updated="data => update(data)"
                />
                <expiration-file label="Certificado de cobertura ART" :name="`${job.key}-art_file`" :checked="false"
                    @updated="data => update(data)"
                />
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="offset-1 col-10">
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
    </div>
</template>

<script>
export default {
    components: {
        'expiration-file': require('./partials/ExpirationFile.vue')
    },
    computed: {
        jobs: function() {
            return this.$store.getters.person.values.working_information.jobs.map(job => {
                let company = this.$store.getters.companies.getById(job.company_id);
                let activity = this.$store.getters.activities.getById(job.activity_id);
                return {
                    key: job.key,
                    company_name: company ? company.name : 'Empresa',
                    activity_name: activity ? activity.name : 'Actividad',
                }
            });
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.documentation.${name}`, value: value });
        }
    }
}
</script>

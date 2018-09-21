<template>
    <div>
        <div class="grey-border">
            <div v-for="job in jobs" :key="job.key">
                <div class="form-row">
                    <div class="col">
                        <hr-label>{{job.company_name + ' | ' + job.activity_name}}</hr-label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12 col-lg-6">
                        <expiration-file    label="Nota de la empresa"
                                            :name="`${job.key}-company_note`" :expiration="documentation[`${job.key}-company_note_expiration`]"
                                            @updated="data => update(data)"
                        />
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <expiration-file    label="Certificado de cobertura ART"
                                            :name="`${job.key}-art_file`" :expiration="documentation[`${job.key}-art_file_expiration`]"
                                            @updated="data => update(data)"
                        />
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Documento de identidad" 
                                        name="dni_copy" :expiration="documentation.dni_copy_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Número de prontuario"
                                        name="pna_file" :expiration="documentation.pna_file_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Registro de conducir"
                                        name="driver_license" :expiration="documentation.driver_license_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Constancia de curso PBIP"
                        name="pbip_file" :expiration="documentation.pbip_file_expiration"
                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Libreta de embarque"
                                        name="boarding_passbook" :expiration="documentation.boarding_passbook_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Cédula de embarque"
                                        name="boarding_card" :expiration="documentation.boarding_card_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Libreta sanitaria"
                                        name="health_notebook" :expiration="documentation.health_notebook_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
                <div class="col-md-12 col-lg-6">
                    <expiration-file    label="Certificado de cobertura Acc. Pers."
                                        name="acc_pers" :expiration="documentation.acc_pers_expiration"
                                        @updated="data => update(data)"
                    />
                </div>
            </div>
        </div>
        <div class="grey-border mt-2">
            <div class="row">
                <div class="col">
                    <label>¿Qué documentos son requeridos para el ingreso?</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <switch-box :label="jobs.length > 1 ? 'Notas de las empresas' : 'Nota de la empresa'"
                                :value="documentation.documents_required.company_note" @update="value => update({name: 'documents_required.company_note', value})"
                    />
                    <el-tooltip v-if="jobs.length > 1" class="item" effect="dark" content="Esta opción afecta a todas las empresas" placement="top">
                        <i class="far fa-question-circle"></i>
                    </el-tooltip>
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box :label="jobs.length > 1 ? 'Certificados de coberturas ART' : 'Certificado de cobertura ART'"
                                :value="documentation.documents_required.art_file" @update="value => update({name: 'documents_required.art_file', value})"
                    />
                    <el-tooltip v-if="jobs.length > 1" class="item" effect="dark" content="Esta opción afecta a todas las aseguradoras" placement="top">
                        <i class="far fa-question-circle"></i>
                    </el-tooltip>
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Documento de identidad"
                                :value="documentation.documents_required.dni_copy" @update="value => update({name: 'documents_required.dni_copy', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Número de prontuario"
                                :value="documentation.documents_required.pna_file" @update="value => update({name: 'documents_required.pna_file', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Registro de conducir"
                                :value="documentation.documents_required.driver_license" @update="value => update({name: 'documents_required.driver_license', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Constancia de curso PBIP"
                                :value="documentation.documents_required.pbip_file" @update="value => update({name: 'documents_required.pbip_file', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Libreta de embarque"
                                :value="documentation.documents_required.boarding_passbook" @update="value => update({name: 'documents_required.boarding_passbook', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Cédula de embarque"
                                :value="documentation.documents_required.boarding_card" @update="value => update({name: 'documents_required.boarding_card', value})"
                    />
                </div>
                <div class="col-md-6 col-lg-4">
                    <switch-box label="Libreta sanitaria"
                                :value="documentation.documents_required.health_notebook" @update="value => update({name: 'documents_required.health_notebook', value})"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <switch-box label="Certificado de cobertura Acc. Pers."
                                :value="documentation.documents_required.acc_pers" @update="value => update({name: 'documents_required.acc_pers', value})"
                    />
                </div>
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
        documentation: function() {
            return this.$store.getters.person.values.documentation;
        },
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
        },
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.documentation.${name}`, value: value });
        }
    }
}
</script>

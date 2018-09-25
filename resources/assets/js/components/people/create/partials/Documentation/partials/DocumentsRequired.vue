<template>
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <switch-box label="Notas de las empresas"
                        :value="documentsRequired.company_note" @update="value => update({name: 'company_note', value})"
            />
            <el-tooltip class="item" effect="dark" content="Esta opción afecta a todas las empresas" placement="top">
                <i class="far fa-question-circle"></i>
            </el-tooltip>
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Certificados de coberturas ART"
                        :value="documentsRequired.art_file" @update="value => update({name: 'art_file', value})"
            />
            <el-tooltip class="item" effect="dark" content="Esta opción afecta a todas las aseguradoras" placement="top">
                <i class="far fa-question-circle"></i>
            </el-tooltip>
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Documento de identidad"
                        :value="documentsRequired.dni_copy" @update="value => update({name: 'dni_copy', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Número de prontuario"
                        :value="documentsRequired.pna_file" @update="value => update({name: 'pna_file', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Registro de conducir"
                        :value="documentsRequired.driver_license" @update="value => update({name: 'driver_license', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Constancia de curso PBIP"
                        :value="documentsRequired.pbip_file" @update="value => update({name: 'pbip_file', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Libreta de embarque"
                        :value="documentsRequired.boarding_passbook" @update="value => update({name: 'boarding_passbook', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Cédula de embarque"
                        :value="documentsRequired.boarding_card" @update="value => update({name: 'boarding_card', value})"
            />
        </div>
        <div class="col-md-6 col-lg-4">
            <switch-box label="Libreta sanitaria"
                        :value="documentsRequired.health_notebook" @update="value => update({name: 'health_notebook', value})"
            />
        </div>
        <div class="col-12">
            <switch-box label="Certificado de cobertura Acc. Pers."
                        :value="documentsRequired.acc_pers" @update="value => update({name: 'acc_pers', value})"
            />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        documentsRequired: {
            type: Object,
            required: true
        }
    },
    methods: {
        update: function({name, value}) {
            this.$store.commit('updateModel', { which: 'person', properties_path: `values.documentation.documents_required.${name}`, value: value });
        }
    }
}
</script>


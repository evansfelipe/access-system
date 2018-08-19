<style lang="scss" scoped>
    a {
        color: black;
        &:hover {
            color: #3F729B;
            text-decoration: none;
        }
    }

    label, small {
        display: block;
    }
</style>

<template>
    <div>
        <!-- Basic information -->
        <div class="row">
            <div class="col-3">
                <label>Nivel de riesgo:</label>
                <strong>{{ personCompany.risk }}</strong>
            </div>
            <div class="col-3">
                <label>Vencimiento  curso PBIP:</label>
                <strong>{{ personCompany.pbip }}</strong>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        <label>ART:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <small>Aseguradora</small>
                        <strong>{{ personCompany.art_company }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Número</small>
                        <strong>{{ personCompany.art_number }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Vencimiento</small>
                        <strong>{{ personCompany.art_expiration }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Separator -->
        <div class="row">
            <div class="col">
                <hr>
            </div>
        </div>
        <!-- Jobs list -->
        <div class="row">
            <div v-for="(job, key) in personCompany.jobs" :key="key" class="col-4">
                <div class="alert alert-primary shadow-sm">
                    <h5>{{ job.company_name }}</h5>
                    {{ job.activity }}
                    <ul>
                        <li v-for="(subactivity, sub_key) in job.subactivities" :key="sub_key">
                            {{ subactivity }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">





        <div class="offset-1 col-5" style="border-right: 1px solid grey">
            <a :href="personCompany.company_url" title="Ver detalles"><h4>{{ personCompany.company_name }}</h4></a>
            <br>
            <div class="row">
                <div class="col-6">
                    <small>CUIT</small>
                    <br>
                    <strong>{{ personCompany.company_cuit }}</strong>
                </div>
                <div class="col-6">
                    <small>Sector</small>
                    <br>
                    <strong>{{ personCompany.company_area }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-6">
                    <small>Actividad / Categoría</small>
                    <br>
                    <strong>{{ personCompany.activity }}</strong>
                </div>
            </div>
        </div>
        <div class="offset-1 col-5">
            <div class="row">
                <div class="col">ART</div>
            </div>
            <div class="row">
                <div class="col-4">
                    <small>Aseguradora</small>
                    <br>
                    <strong>{{ personCompany.art_company }}</strong>
                </div>
                <div class="col-4">
                    <small>Número</small>
                    <br>
                    <strong>{{ personCompany.art_number }}</strong>
                </div>
                <div class="col-4">
                    <small>Vencimiento</small>
                    <br>
                    <strong>{{ personCompany.art_expiration }}</strong>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col">
                    Vencimiento curso PBIP
                    <br>
                    <strong>{{ personCompany.pbip }}</strong>
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
export default {
    props: {
        personCompany: {
            required: true,
            type: Object
        }
    }
}
</script>


<style lang="scss" scoped>
    a {
        color: black;
        &:hover {
            color: #3F729B;
            text-decoration: none;
        }
    }
</style>


<template>
    <div class="row">
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
    </div>
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


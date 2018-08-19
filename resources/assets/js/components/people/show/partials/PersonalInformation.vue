<style lang="scss" scoped>
    small { display: block }
    div.row + div.row { margin-top: 15px }
</style>

<template>
    <div> <!-- Prevents d-flex problem with v-show -->
        <div class="row d-flex align-items-center">
            <div class="col-md-4 text-center">
                <div class="offset-1 col-10">
                    <img class="img-fluid rounded-circle shadow-sm" :src="person.picture_path">
                    <br>
                    <button class="btn mt-2 btn-sm btn-outline-info" @click="showPictures">Ver más</button>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Last name & name -->
                <h3 class="mb-3">{{ person.full_name }}</h3>
                <!-- Record, document & cuil -->
                <div class="row">
                    <div class="col-4">
                        <small>Legajo</small>
                        <strong>{{ person.record }}</strong>
                    </div>
                    <div class="col-4">
                        <small>{{ person.document_type }}</small>
                        <strong>{{ person.document_number }}</strong>
                    </div>
                    <div class="col-4">
                        <small>CUIL / CUIT</small>
                        <strong>{{ person.cuil }}</strong>
                    </div>
                </div>
                <!-- Birthday, birth country & sex -->
                <div class="row">
                    <div class="col-4">
                        <small>Fecha de nacimiento</small>
                        <strong>{{ person.birthday }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Nacionalidad</small>
                        <strong>{{ person.birth_country }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Género</small>
                        <strong>{{ person.sex }}</strong>
                    </div>
                </div>
                <!-- Blood type, pna & email -->
                <div class="row">
                    <div class="col-4">
                        <small>Grupo y factor sanguíneo</small>
                        <strong>{{ person.blood_type }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Prontuario PNA</small>
                        <strong>{{ person.pna }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Email</small>
                        <strong>{{ person.email }}</strong>
                    </div>
                </div>
                <!-- Phone, mobile phone & fax -->
                <div class="row">
                    <div class="col-4">
                        <small>Teléfono fijo</small>
                        <strong>{{ person.phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Teléfono móvil</small>
                        <strong>{{ person.mobile_phone }}</strong>
                    </div>
                    <div class="col-4">
                        <small>FAX</small>
                        <strong>{{ person.fax }}</strong>
                    </div>
                </div>
                <!-- Address, apartment & cp -->
                <div class="row">
                    <div class="col-4">
                        <small>Dirección</small>
                        <strong>{{ person.address }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Departmaneto</small>
                        <strong>{{ person.apartment }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Código Postal</small>
                        <strong>{{ person.cp }}</strong>
                    </div>
                </div>
                <!-- City, province & country -->
                <div class="row">
                    <div class="col-4">
                        <small>Ciudad</small>
                        <strong>{{ person.city }}</strong>
                    </div>
                    <div class="col-4">
                        <small>Provincia / Estado</small>
                        <strong>{{ person.province }}</strong>
                    </div>
                    <div class="col-4">
                        <small>País</small>
                        <strong>{{ person.country }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pictures panel -->
        <modal-wrapper :visible="pictures.opened" @closed="pictures.opened = false">
            <loading-cover v-if="pictures.loading" message="Cargando..."/>
            <div id="pictures-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div v-for="(picture, key) in pictures.list" :key="key" :class="`carousel-item ${key === 0 ? 'active' : ''}`">
                        <img class="d-block w-100" :src="picture">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#pictures-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#pictures-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>
        </modal-wrapper>
    </div>
</template>

<script>
export default {
    props: {
        person: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            pictures: {
                list: [],
                loading: false,
                opened:  false,
            }
        }
    },
    methods: {
        showPictures: function() {
            this.pictures.opened = true;
            this.pictures.loading = true;
            axios.get(`people/${this.$route.params.id }/pictures`)
            .then(response => {
                this.pictures.list = response.data;
                this.pictures.loading = false;
            })
            .catch(error => console.log(error))
        }
    }
}
</script>



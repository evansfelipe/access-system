<style lang="scss" scoped>
    td.small {
        padding-right: 0.5em;
        font-size: 75%;
    }

    td.strong {
        font-weight: bold;
    }

    div.card-body {
        padding: 0.5em !important;
    }

    div.content {
        margin: 1em;
        border: 1px solid rgb(222,222,222);
        padding: 1em;
        border-radius: 5px;
    }

    textarea.form-control {
        resize: none;
        height: auto;
    }

    .container-enter-active, .container-leave-active { transition: all .3s }
    .container-enter, .container-leave-to { opacity: 0 }
    .container-leave-active { max-height: 100vh }
    .container-leave-to {
        max-height: 0;
    }

</style>

<template>
    <div class="container">

        <rt-camera source="http://184.72.239.149/vod/smil:BigBuckBunny.smil/playlist.m3u8"/>

        <div v-if="disconnected" class="alert alert-danger">
            Se ha perdido la conexión con el servidor. {{ this.connecting_attempts }}º intento de reconección. <i class="fas fa-spinner fa-spin fa-lg centered"></i>
            <button class="btn btn-link" @click="forceReconnectionMQTT">Reconectar manualmente</button>
        </div>

        <loading-cover v-if="connecting"/>

        <ul class="nav nav-tabs">
            <tab-item v-for="(p,key) in people" :key="key" :active="tab === key" @click.native="changeTab(key)">
                <div class="d-inline-block mr-2" @click="closeTab(key)"><i class="fas fa-times"></i></div>
                {{ p.values.full_name }}
            </tab-item>
        </ul>
        <div class="card card-default borderless-top">
            <div class="card-body" style="padding: 2em 3em;">
                <template v-if="person">
                    <div class="form-row">
                        <div class="col-4">
                            <div class="content text-center">
                                <h3 class="text-center">{{ person.values.full_name }}</h3>
                                <span v-if="person.access.allowed" class="badge badge-success">Acceso permitido</span>
                                <span v-else class="badge badge-danger">Acceso denegado</span>
                                <div class="p-3"><img class="img-fluid rounded-circle shadow-sm" :src="person.values.picture_path"></div>
                                <div class=" d-flex justify-content-center text-left"><access-card :number="person.values.card.number" :from="person.values.card.from" :until="person.values.card.until"/></div>
                                <table class="m-2 text-left">
                                    <tr>
                                        <td class="small">Riesgo</td>
                                        <td class="strong">{{ person.values.risk }}</td>
                                    </tr>
                                    <tr>
                                        <td class="small">{{ person.values.document_type }}</td>
                                        <td class="strong">{{ person.values.document_number }}</td>
                                    </tr>
                                    <tr v-if="person.values.company">
                                        <td class="small">Empresa</td>
                                        <td class="strong">{{ person.values.company }}</td>
                                    </tr>
                                    <tr>
                                        <td class="small">Actividad</td>
                                        <td class="strong">{{ person.values.activity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="small">Subactividad {{ person.values.subactivities.length > 1 ? 'es' : '' }}</td>
                                        <td class="strong">{{ person.values.subactivities.join(', ') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-row" style="padding: 1em">
                                <div class="col-6">
                                    <button class="btn btn-outline-success btn-block" @click="allowed(person)">Permitir acceso</button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-danger btn-block" @click="denied(person)">Denegar acceso</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="content">
                                        <h5 class="mb-2 d-inline-block">Vehículos</h5>
                                        <div class="d-inline-block float-right">
                                            <input v-if="person.vehicles_search" v-model="person.vehicles_search_input" type="text" placeholder="Búsqueda" class="md-input" ref="vehicles_search">
                                            <div class="d-inline cursor-pointer" @click="toggleVehicleSearch"><i class="fas fa-search cursor-pointer"></i></div>
                                        </div>
                                        <cards-carousel :list="person.values.vehicles"
                                                        :selected="person.vehicle"
                                                        :keys="['type','plate','brand','model','year','colour']"
                                                        :filter="person.vehicles_search_input"
                                                        @selection="id => person.selectVehicle(id)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <transition name="container" tag="div">
                                <div v-if="person.allowsContainer()" class="row">
                                    <div class="col">
                                        <div class="content">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="mr-3 d-inline">Contenedor</h5>
                                                    <switch-box @update="value => person.setContainer(value)"/>
                                                </div>
                                            </div>
                                            <transition name="container" tag="div">
                                            <template v-if="person.container !== null">
                                                
                                                <div class="form-row mt-3">
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="serial_number" class="form-control form-control-sm" placeholder="Número de serie"
                                                                    v-model="person.container.serial_number"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="brand" class="form-control form-control-sm" placeholder="Marca"
                                                                    v-model="person.container.brand"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="model" class="form-control form-control-sm" placeholder="Modelo"
                                                                    v-model="person.container.model"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="format" class="form-control form-control-sm" placeholder="Formato"
                                                                    v-model="person.container.format"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="size" class="form-control form-control-sm" placeholder="Tamaño"
                                                                    v-model="person.container.size"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-4" :errors="[]">
                                                        <div class="col">
                                                            <input type="text" name="colour" class="form-control form-control-sm" placeholder="Color"
                                                                    v-model="person.container.colour"
                                                            >
                                                        </div>
                                                    </form-item>
                                                    <form-item col="col-12" :errors="[]">
                                                        <div class="col">
                                                            <textarea class="form-control form-control-sm" rows="2" placeholder="Observaciones del contenedor"
                                                                    v-model="person.container.observation"
                                                            ></textarea>
                                                        </div>
                                                    </form-item>
                                                </div>
                                                
                                            </template>
                                            </transition>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                            <div class="row">
                                <div class="col">
                                    <div class="content">
                                        <h5 class="mb-2">Observaciones</h5>
                                        <div class="form-row">
                                            <div class="col-10">
                                                <textarea class="form-control" v-model="person.textarea" rows="2" placeholder="Nueva observación"></textarea>
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-block btn-outline-primary" style="height:100%" @click="newObservation">Enviar</button>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <custom-table
                                                    :columns="observations_columns"
                                                    :rows="person.values.observations"
                                                    :rowsquantity="5"
                                                    :no-rows-message="'No hay observaciones'"
                                                    @rowclicked="toggleObservation"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>

class Person {
    constructor(values, access) {
        this.values = values;
        this.access = access;

        this.values.observations.forEach(observation => {
            observation.collapsed = true;
            observation.original = observation.text;
            observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
            observation.text = observation.abbreviated;
        });

        this.textarea = "";

        this.vehicles_search_input = "";
        this.vehicles_search = false;
        this.vehicle = null;
        this.container = null;

    }

    addObservation(observation) {
        observation.collapsed = true;
        observation.original = observation.text;
        observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
        observation.text = observation.abbreviated;
        this.values.observations.unshift(observation);
    }

    selectVehicle(id) {
        this.vehicle = this.vehicle === id ? null : id;
        if(!this.allowsContainer()) {
            this.container = null;
        }
    }

    allowsContainer() {
        let vehicle = this.values.vehicles.getById(this.vehicle);
        return vehicle !== undefined ? vehicle.allows_container : false;
    }

    setContainer(value) {
        if(value) {
            this.container = {
                serial_number : '',
                brand:          '',
                model:          '',
                format:         '',
                size:           '',
                colour:         '',
                observation:    ''
            }
        }
        else {
            this.container = null;
        }
    }
}

export default {
    components: {
        'rt-camera': require('./partials/Camera.vue'),
        'cards-carousel': require('./partials/CardsCarousel.vue'),
    },
    data() {
        return {
            connecting: true,
            connecting_attempts: 0,
            disconnected: true,
            client: null,
            people: [],
            person: null,
            tab: 0,
            observations_columns: [
                {name: 'date', text: 'Fecha',   width: '20'},
                {name: 'user', text: 'Usuario', width: '20'},
                {name: 'text', text: 'Texto',   width: '60'}
            ]
        };
    },
    mounted() {
        this.connectMQTT();
    },
    methods: {
        createMQTT: function() {
            if(this.client === null) {
                this.client = new Messaging.Client(
                    "mqtt.fi.mdp.edu.ar",
                    9001,
                    "myclientid_" + parseInt(Math.random() * 100, 10)
                );

                this.client.onConnectionLost = (response) => {
                    this.disconnected = true;
                    this.connectMQTT();
                };

                this.client.onMessageArrived = (message) => {
                    // Parses the JSON message.
                    let object = JSON.parse(message.payloadString);
                    // Checks if the message has the correct format.
                    if(object.hasOwnProperty('ID') && object.ID) {
                        // Gets the data associated with the card number.
                        axios.get('security/person/' + object.ID)
                        .then(response => {
                            let access = {
                                device: object.N,
                                zone: object.A,
                                card_number: object.ID,
                                allowed: object.Ac == "OK" ? true : false,
                                date: object.F
                            };
                            let person = new Person(response.data, access);
                            this.person = person;
                            this.people.push(person);
                            this.tab = this.people.length - 1;

                        })
                        .catch(error => {
                            console.log(error);
                        });
                    }
                };
            }
        },
        connectMQTT: function() {
            this.createMQTT();

            this.client.connect({
                timeout: 3,
                onSuccess: () => {
                    this.client.subscribe('testtopic/Usuario', {qos: 2});
                    this.disconnected = false;
                    this.connecting = false;
                    this.connecting_attempts = 0;
                },
                onFailure: (message) => {
                    this.disconnected = true;
                    this.connecting = false;
                    this.connecting_attempts++;
                    setTimeout(() => {
                        this.connectMQTT()
                    }, 60000);
                }
            });
        },
        forceReconnectionMQTT: function() {
            this.client = null;
            this.connectMQTT();
        },
        changeTab: function(key) {
            this.tab = key;
            this.person = this.people[key];
        },
        closeTab: function(key) {
            if(this.tab !== key && key < this.tab) {
                this.tab--;
            }
            else if (this.tab === key) {
                this.tab = (key - 1 < 0) ? 0 : key - 1;
            }
            this.people.splice(key, 1);
            this.person = this.people[this.tab];
        },
        newObservation: function() {
            axios.post(`/security/person/${this.person.values.id}/new-observation`, {text: this.person.textarea})
                .then(response => {
                    this.person.textarea = '';
                    this.person.addObservation(response.data);
                })
                .catch(error => console.log(error));
        },
        toggleObservation: function(observation) {
            observation.text = observation.collapsed ? observation.original : observation.abbreviated;
            observation.collapsed = !observation.collapsed;
        },
        toggleVehicleSearch: function() {
            this.person.vehicles_search = !this.person.vehicles_search;
            this.person.vehicles_search_input = "";
            if(this.person.vehicles_search) {
                this.$nextTick(() => this.$refs.vehicles_search.focus())
            }
        },
        modal: function(text, callback) {
            this.$prompt(text, 'Ingrese un comentario', {
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    let msg = value.trim();
                    return msg.length !== 0;
                }
            }).then(({ value }) => callback(value)).catch(() => {});
        },
        allowed: function(person) {
            let data = {person: person.values.id, vehicle: person.vehicle, container: person.container, action: person.access.allowed, access: true};
            if(!person.access.allowed) {
                this.modal('¿Por qué deja pasar a una persona con acceso denegado?', (value) => {
                    data.observation = value;
                    this.storeEntrance(data);
                });
            }
            else {
                this.storeEntrance(data);
            }
        },
        denied: function(person) {
            let data = {card: person.values.card.number, vehicle: person.vehicle, container: person.container, action: person.access.allowed, access: false};
            if(person.access.allowed) {
                this.modal('¿Por qué prohibe el paso a una persona con acceso permitido?', (value) => {
                    data.observation = value;
                    this.storeEntrance(data);
                });
            }
            else {
                this.storeEntrance(data);
            }
        },
        storeEntrance: function(data) {
            axios.post(`/security/entrance`, data)
                .then(response => {
                    this.closeTab(this.tab);
                })
                .catch(error => console.log(error));
        }
    }
}
</script>


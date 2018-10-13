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
                            <div class="content">
                                <h3 class="text-center">{{ person.values.full_name }}</h3>
                                <div class="p-3"><img class="img-fluid rounded-circle shadow-sm" :src="person.values.picture_path"></div>
                                <div class=" d-flex justify-content-center"><access-card :number="person.values.card.number" :from="person.values.card.from" :until="person.values.card.until"/></div>
                                <table class="m-2">
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
                            <div v-if="person.containers.length !== 0" class="row">
                                <div class="col">
                                    <div class="content">
                                        <h5 class="mb-2 d-inline-block">Containers</h5>
                                        <div class="d-inline-block float-right">
                                            <input v-if="person.containers_search" v-model="person.containers_search_input" type="text" placeholder="Búsqueda" class="md-input" ref="containers_search">
                                            <div class="d-inline cursor-pointer" @click="toggleContainerSearch"><i class="fas fa-search cursor-pointer"></i></div>
                                        </div>
                                        <cards-carousel :list="person.containers"
                                                        :selected="person.container"
                                                        :keys="['series_number','format','size','brand','model','colour']"
                                                        :filter="person.containers_search_input"
                                                        @selection="id => person.selectContainer(id)"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="content">
                                        <h5 class="mb-2">Observaciones</h5>
                                        <div class="form-row">
                                            <div class="col-10">
                                                <textarea class="form-control" v-model="person.textarea" rows="2" placeholder="Nueva observación"></textarea>
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-block btn-outline-success" style="height:100%" @click="newObservation">Enviar</button>
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
    constructor(values) {
        this.values = values;

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

        this.containers = [];
        this.container = null;
        this.containers_search_input = "";
        this.containers_search = false;
    }

    addObservation(observation) {
        observation.collapsed = true;
        observation.original = observation.text;
        observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
        observation.text = observation.abbreviated;
        this.values.observations.unshift(observation);
    }

    selectVehicle(id) {
        console.log(this);
        
        this.vehicle = this.vehicle === id ? null : id;
        this.container = null;
        if(this.vehicle !== null) {
            let vehicle_pos = this.values.vehicles.getPositionById(id);
            this.containers = this.values.vehicles[vehicle_pos].containers;
        }
        else {
            this.containers = [];
        }
    }

    selectContainer(id) {
        this.container = this.container === id ? null : id;
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
                    if(object.hasOwnProperty('card_number') && object.card_number) {
                        // Gets the data associated with the card number.
                        axios.get('security/person/' + object.card_number)
                        .then(response => {
                            console.log(response.data);
                            
                            let person = new Person(response.data);
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
        toggleContainerSearch: function() {
            this.person.containers_search = !this.person.containers_search;
            this.person.containers_search_input = "";
            if(this.person.containers_search) {
                this.$nextTick(() => this.$refs.containers_search.focus())
            }
        }
    }
}
</script>


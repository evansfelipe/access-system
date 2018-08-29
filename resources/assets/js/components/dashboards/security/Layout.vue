<template>
    <div class="container">
        <ul class="nav nav-tabs">
            <tab-item v-for="(p,key) in people" :key="key" :active="tab === key" @click.native="changeTab(key)">
                <div class="d-inline-block" @click="closeTab(key)"><i class="fas fa-times"></i></div>
                {{ p.full_name }}
            </tab-item>
        </ul>
        <div class="card card-default borderless-top-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img class="img-fluid rounded-circle shadow-sm" :src="person.picture_path">
                    </div>
                    <div class="col-8">
                        <h3 class="text-center">{{ person.full_name }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            client: null,
            people: [],
            person: {},
            tab: 0
        };
    },
    mounted() {
        this.create();
    },
    methods: {
        create: function() {
            this.client = new Messaging.Client(
                "mqtt.fi.mdp.edu.ar",
                9001,
                "myclientid_" + parseInt(Math.random() * 100, 10)
            );
            this.client.onConnectionLost = (response) => {};
            this.client.onMessageArrived = (message) => {
                let object = JSON.parse(message.payloadString);
                if(object.hasOwnProperty('card_number')) {
                    axios.get('security/person/' + object.card_number)
                        .then(response => {
                            this.person = response.data;
                            this.people.push(response.data);
                            this.tab = this.people.length - 1;
                        })
                        .catch(error => {
                            console.log(error);
                        })
                }
            };
            this.client.connect({
                timeout: 3,
                //Gets Called if the connection has sucessfully been established
                onSuccess: () => {
                    this.client.subscribe('testtopic/#', {qos: 2});
                },
                //Gets Called if the connection could not be established
                onFailure: (message) => {
                }
            });
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
            
        }
    }
}
</script>


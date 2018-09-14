<style lang="scss" scoped>
    textarea.form-control {
        height: auto;
        resize: none;
    }
</style>

<template>
    <div>
        <div class="form-row">
            <div class="col-10">
                <textarea class="form-control" v-model="observation" rows="2" placeholder="Nueva observación"></textarea>
            </div>
            <div class="col-2">
                <button class="btn btn-block btn-outline-success" style="height:100%" @click="newObservation">Enviar</button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <custom-table
                    :columns="columns"
                    :rows="observations"
                    :rowsquantity="5" 
                    @rowclicked="toggleObservation"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        personObservations: {
            type:     Array,
            required: true,
        }
    },
    data() {
        return {
            observation: '',
            observations: this.personObservations,
            columns: [
                {name: 'date', text: 'Fecha',   width: '15'},
                {name: 'user', text: 'Usuario', width: '15'},
                {name: 'text', text: 'Texto',   width: '70'}
            ]
        }
    },
    mounted() {
        this.setObservations();
    },
    watch: {
        personObservations: function() {
            this.observations = this.personObservations;
            this.setObservations();
        }
    },
    methods: {
        setObservations: function() {
            this.observations.forEach(observation => {
                observation.original = observation.text;
                observation.collapsed = true;
                observation.abbreviated = observation.text.length > 100 ? observation.text.substring(0, 99) + '...': observation.text;
                observation.text = observation.abbreviated;
            });
        },
        toggleObservation: function(observation) {
            observation.text = observation.collapsed ? observation.original : observation.abbreviated;
            observation.collapsed = !observation.collapsed;
        },
        newObservation: function() {
            let person_id = this.$route.params.id;            
            axios.post(`/people/${person_id}/new-observation`,{text: this.observation})
                .then(response => {
                    this.observation = '';
                    this.observations.unshift(response.data);
                    console.log(this.observations);
                    
                })
                .catch(error => console.log(error));
        }
    }
}
</script>

<template>
    <div>
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <!-- General information tab -->
            <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-box">
                Container
            </tab-item>
        </ul>
        <!-- Content -->
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <general-information v-show="tab === 0" :values="values"/>
        </show-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information':   require('./partials/GeneralInformation.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            values: {}
        };
    },
    beforeMount() {
        axios.get(`/containers/${this.$route.params.id}`)
            .then(response => {
                console.log(response.data);
                
                this.axios_finished = true;
                this.values = response.data;
            })
            .catch(error => console.log(error));
    },
    methods: {
        edit: function() {
            this.$store.dispatch('fetchModel', { which: 'container', id: this.$route.params.id });
            this.$router.push(`/containers/create`);
        },
        pdf: function() {
            
        }
    }
}
</script>
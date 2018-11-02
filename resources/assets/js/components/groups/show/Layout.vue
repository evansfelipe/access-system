<template>
    <div>
        <show-wrapper :loading="!axios_finished" @edit="edit" @pdf="pdf">
            <!-- Tabs -->
            <ul slot="tabs" class="nav nav-tabs">
                <!-- General information tab -->
                <tab-item :active="tab === 0" @click.native="tab = 0" icon="fas fa-universal-access">
                    Grupo
                </tab-item>
            </ul>
            <!-- Content -->
            <general-information v-show="tab === 0" :values="general_information"/>
            <assigned-people v-show="tab === 0" :values="general_information.people"/>
        </show-wrapper>
    </div>
</template>

<script>
export default {
    components: {
        'general-information':   require('./partials/GeneralInformation.vue'),
        'assigned-people':   require('./partials/AssignedPeople.vue'),
    },
    data: function() {
        return {
            axios_finished: false,
            tab: 0,
            edit_route: "",
            general_information: {},
        };
    },
    beforeMount() {
        axios.get(`/groups/${this.$route.params.id}`)
            .then(response => {
                this.axios_finished = true;
                this.general_information = response.data;
            })
            .catch(error => console.log(error));
    },
    methods: {
        edit: function() {
            this.$store.dispatch('fetchModel', { which: 'group', id: this.$route.params.id });
            this.$router.push(`/groups/create`);
        },
        pdf: function() {
            
        }
    }
}
</script>

function fetch({ commit, state }, what) {
    commit(`updating`, {what, value: true});
    axios.get(`/${what}/updated_at`)
    .then(response => {
        let new_timestamp = new Date(response.data.updated_at);
        if(state[what].timestamp === null || state[what].timestamp < new_timestamp) {
            axios.get(`/${what}/list`)
            .then(response => commit(`set`, {what, data: response.data, timestamp: new_timestamp}))
            .catch(error => console.log(error))
            .finally(() => commit(`updating`, {what, value: false}));
        }
        else {
            commit(`updating`, {what, value: false});
        }
    })
    .catch(error => console.log(error));
}

export default {
    state: {
        vehicles: {
            timestamp: null,
            updating: false,
            list: []
        },
        companies: {
            timestamp: null,
            updating: false,
            list: []
        },
        activities: {
            timestamp: null,
            updating: false,
            list: []
        },
    },
    getters: {
    },
    mutations: {
        updating: function(state, {what, value}) {
            state[what].updating = value;
        },
        set: function(state, {what, data, timestamp}) {
            state[what].list = data;
            state[what].timestamp = timestamp;
        }
    },
    actions: {
        fetch: function({ commit, state }, what) {
            fetch({ commit, state }, what)
        },
    }
};
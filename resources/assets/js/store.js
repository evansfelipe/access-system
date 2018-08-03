const debug = true;
export default {
    state: {
        debug: debug,
        people: {
            timestamp: null,
            updating: false,
            list: []
        },
        companies: {
            timestamp: null,
            updating: false,
            list: []
        },
        vehicles: {
            timestamp: null,
            updating: false,
            list: []
        },
        activities: {
            timestamp: null,
            updating: false,
            list: []
        },
        notifications: {
            id: 0,
            list: []
        },
        models: {
            person: {
                updating: false,
                values: require(`./models/person.js`).default.default(debug)
            },
        }
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
        },
        updateModel: function(state, {which, properties_path, value}) {
            if(state.debug) console.log('Updating: ', which, properties_path, value);
            if(properties_path.trim().length < 1) {
                state.models[which].values = value;
            }
            else {
                let variable = state.models[which].values;
                let properties = properties_path.split('.');
                for(let i = 0; i < properties.length - 1; i++) {
                    variable = variable[properties[i]];
                }
                variable[properties[properties.length - 1]] = value;
            }
        },
        resetModel: function(state, which) {
            if(state.debug) console.log('Restarting model: ', which);
            state.models[which].values = require(`./models/${which}.js`).default.default();
        },
        /**
         * Given a type and a message, creates and adds a new notification with an unique id
         * to the array of notifications.
         */
        notification: function(state, {type, message}) {
            let id = state.notifications.id++;
            if(state.debug) console.log('Adding notification: ', { id, type, message });
            state.notifications.list.push({ id, type, message });
        },
        /**
         * Given an id, removes the notification that matches that id from the array of notifications.
         * If there isn't a notification with the given id, then does nothing.
         */
        removeNotification: function(state, notification_id) {
            if(state.debug) console.log('Removing notification with id: ', notification_id);
            let index = state.notifications.list.getPositionById(notification_id);
            if(index !== undefined) {
                state.notifications.list.splice(index, 1);
            }
        }
    },
    actions: {
        fetch: function({ commit, state }, what) {
            if(state.debug) console.log('Validating timestamps: ', what);
            commit(`updating`, { what, value: true });
            axios.get(`/${what}/updated_at`)
            .then(response => {
                let new_timestamp = new Date(response.data.updated_at);
                if(state[what].timestamp === null || state[what].timestamp < new_timestamp) {
                    if(state.debug) console.log('Fetching: ', what);
                    axios.get(`/${what}/list`)
                    .then(response => {
                        if(state.debug) console.log('Fetch success: ', what);
                        commit(`set`, {what, data: response.data, timestamp: new_timestamp});
                    })
                    .catch(error => {
                        if(state.debug) console.log('Fetch failed: ', what);
                        console.log(error);
                    })
                    .finally(() => commit(`updating`, {what, value: false}));
                }
                else {
                    if(state.debug) console.log('Fetch not needed: ', what);
                    commit(`updating`, {what, value: false});
                }
            })
            .catch(error => {
                if(state.debug) console.log('Error while validating timestamps: ', what);
                console.log(error);
            });
        },
        fetchPerson: function({ commit, state }, id) {
            if(state.debug) console.log('Fetching person with id: ', id);
            state.models.person.updating = true;
            axios.get(`/people/${id}/edit`)
            .then(response => {
                commit('updateModel', {which: 'person', properties_path: '', value: response.data});
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                state.models.person.updating = false;
            });
        }
    }
};
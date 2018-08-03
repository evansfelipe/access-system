const debug = true;
export default {
    strict: true,
    state: {
        debug: debug,
        ui: {
            loading: {
                state: false,
                message: ''
            },
            notifications: {
                id: 0,
                list: []
            },
            sidebar: {
                opened: true
            }
        },
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
        models: {
            person: {
                updating: false,
                values: require(`./models/person.js`).default.default(false)
            },
            company: {
                updating: false,
                values: require('./models/company.js').default.default(debug)
            }
        }
    },
    getters: {
        notifications: function(state) {
            return state.ui.notifications.list;
        },
    },
    mutations: {
        loading: function(state, values) {
            if(state.debug) console.log('UI loading:', values.state, 'Message:', values.message);
            state.ui.loading.state = values.state;
            state.ui.loading.message = values.message;
        },
        /**
         * Changes the status of sidebar to the given value.
         */
        toggleSidebar: function(state, value) {
            if(state.debug) console.log('Toggling sidebar');
            state.ui.sidebar.opened = value;
        },
        /**
         * Given a type and a message, creates and adds a new notification with an unique id
         * to the array of notifications.
         */
        addNotification: function(state, notification) {
            if(state.debug) console.log('Adding notification: ', notification);
            state.ui.notifications.list.push(notification);
            // Increases the id for the next notification. Done here because
            // it can't be done in the 'addNotification' action.
            state.ui.notifications.id++;
        },
        /**
         * Given an id, removes the notification that matches that id from the array of notifications.
         * If there isn't a notification with the given id, then does nothing.
         */
        removeNotification: function(state, notification_id) {
            let index = state.ui.notifications.list.getPositionById(notification_id);
            if(index !== undefined) {
                if(state.debug) console.log('Removing notification with id: ', notification_id);
                state.ui.notifications.list.splice(index, 1);
            }
        },
        updating: function(state, {what, value}) {
            state[what].updating = value;
        },
        set: function(state, {what, data, timestamp}) {
            state[what].list = data;
            state[what].timestamp = timestamp;
        },
        updateModel: function(state, {which, properties_path, value}) {
            if(state.debug) console.log('Updating model: ', which, properties_path, value);
            if(properties_path.trim().length < 1) {
                state.models[which] = value;
            }
            else {
                let variable = state.models[which];
                let properties = properties_path.split('.');
                for(let i = 0; i < properties.length - 1; i++) {
                    variable = variable[properties[i]];
                }
                variable[properties[properties.length - 1]] = value;
            }
        },
        /**
         * Given a model name, resets this model to the default value.
         */
        resetModel: function(state, which) {
            if(state.debug) console.log('Restarting model: ', which);
            state.models[which].values = require(`./models/${which}.js`).default.default();
        },
        /**
         * Given a vehicle id, puts in the picked attribute the given value.
         * If there isn't a vehicle with the given id, then does nothing.
         */
        pickVehicle: function(state, id) {
            if(state.vehicles.list.getById(id) !== undefined) {
                let pos = state.models.person.values.assign_vehicles.vehicles_id.indexOf(id);
                if(pos !== -1) {
                    state.models.person.values.assign_vehicles.vehicles_id.splice(pos, 1);
                }
                else {
                    state.models.person.values.assign_vehicles.vehicles_id.push(id);
                }
                if(state.debug) console.log('Vehicles picked:', state.models.person.values.assign_vehicles.vehicles_id);
            }
        },
        /**
         * Unpicks each vehicle from the list of vehicles.
         */
        unpickAllVehicles: function(state) {
            state.vehicles.list.forEach(vehicle => vehicle.picked = false);
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
        addNotification: function({ commit, state }, { type, message }) {
            let id = state.ui.notifications.id;
            commit('addNotification', { id, type, message });
            setTimeout(() => commit('removeNotification', id), 5000);
        },
        fetchPerson: function({ commit, state }, id) {
            if(state.debug) console.log('Fetching person with id: ', id);
            commit('updateModel', {which: 'person', properties_path: 'updating', value: true});
            axios.get(`/people/${id}/edit`)
            .then(response => {
                commit('updateModel', {which: 'person', properties_path: 'values', value: response.data});
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => commit('updateModel', {which: 'person', properties_path: 'updating', value: false}));
        },
    }
};
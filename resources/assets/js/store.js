const debug = true;

class List {
    constructor() {
        this.timestamp = null;
        this.updating  = false;
        this.list      = [];
    }

    getById(id) {
        return this.list.getById(id);
    }
}

class Model {
    constructor(name, plural) {
        this.id       = null;
        this.plural   = plural;
        this.name     = name;
        this.updating = false;
        this.editing  = false;
        this.modified = false;
        this.values   = require(`./models/${this.name}.js`).default.default(debug);
    }

    restart() {
        this.id       = null;
        this.updating = false;
        this.editing  = false;
        this.modified = false;
        this.values   = require(`./models/${this.name}.js`).default.default(false);
    }
}

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
                next_id: 0,
                list: []
            },
            sidebar_opened: true
        },
        lists: {
            people:     new List(),
            vehicles:   new List(),
            companies:  new List(),
            activities: new List()
        },
        models: {
            person:  new Model('person', 'people'),
            company: new Model('company', 'companies'),
            vehicle: new Model('vehicle', 'vehicles'),
        }
    },
    getters: {
        /**
         * User Interface
         */
        loading: function({ui}) {
            return ui.loading;
        },
        notifications: function({ui}) {
            return ui.notifications.list;
        },
        sidebar_opened: function({ui}) {
            return ui.sidebar_opened;
        },
        /**
         * Lists
         */
        people: function({lists}) {
            return lists.people;
        },
        vehicles: function({lists}) {
            return lists.vehicles;
        },
        companies: function({lists}) {
            return lists.companies;
        },
        activities: function({lists}) {
            return lists.activities;
        },
        /**
         * Models
         */
        person: function({models}) {
            return models.person;
        },
        company: function({models}) {
            return models.company;
        },
        vehicle: function({models}) {
            return models.vehicle;
        }
    },
    mutations: {
        loading: function({debug, ui}, values) {
            ui.loading.state = values.state;
            ui.loading.message = values.message;
            if(debug) console.log('UI-Loading:', ui.loading.state, ui.loading.message);
        },
        /**
         * Changes the status of sidebar to the given value.
         */
        toggleSidebar: function(state, value) {
            if(state.debug) console.log('Toggling sidebar');
            state.ui.sidebar_opened = value;
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
            state.ui.notifications.next_id++;
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
        updatingList: function(state, {what, value}) {
            state.lists[what].updating = value;
        },
        set: function(state, {what, data, timestamp}) {
            state.lists[what].list = data;
            state.lists[what].timestamp = timestamp;
        },
        updateModel: function(state, {which, properties_path, value}) {
            if(state.debug) console.log('Updating model: ', which, properties_path, value);
            state.models[which].modified = true;
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
            if(state.debug) console.log('Restarting model:', which);
            state.models[which].restart();
        },
        /**
         * Given a vehicle id, puts in the picked attribute the given value.
         * If there isn't a vehicle with the given id, then does nothing.
         */
        pickVehicle: function(state, id) {
            if(state.lists.vehicles.list.getById(id) !== undefined) {
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
        pickPerson: function(state, id) {
            if(state.lists.people.list.getById(id) !== undefined) {
                let pos = state.models.vehicle.values.assign_people.people_id.indexOf(id);
                if(pos !== -1) {
                    state.models.vehicle.values.assign_people.people_id.splice(pos, 1);
                }
                else {
                    state.models.vehicle.values.assign_people.people_id.push(id);
                }
                if(state.debug) console.log('People picked:', state.models.vehicle.values.assign_people.people_id);
            }
        },
        /**
         * Unpicks each vehicle from the list of vehicles.
         */
        unpickAllVehicles: function(state) {
            state.models.person.values.assign_vehicles.vehicles_id = [];
        },
        unpickAllPeople: function(state) {
            state.models.vehicle.values.assign_people.people_id = [];
        },


        /**
         * Adds a new job to the jobs list of the person's model
         */
        addJob: function(state) {
            state.models.person.values.working_information.jobs.push({
                key: Date.now(),
                company_id: '',
                activity_id: '',
                subactivities: [],
                groups: [],
                art_company: 'ART Test',
                art_number: '123456',
                cards: [{ key: Date.now(), number: '', from: '', until: '' }],
            });
        },
        /**
         * Given a job of the jobs list of the person's model, updates its values.
         */
        updateJob: function(state, {job, attribute, value}) {
            let pos = state.models.person.values.working_information.jobs.indexOf(job);
            let ref = state.models.person.values.working_information.jobs[pos];
            ref[attribute] = value;
        },
        /**
         * Given a job, removes it from the jobs list of the person's model.
         */
        deleteJob: function(state, job) {
            let pos = state.models.person.values.working_information.jobs.indexOf(job);
            if(pos !== -1) {
                state.models.person.values.working_information.jobs.splice(pos, 1);
            }
        },
        /**
         * Given a job, adds a new card to its cards list.
         */
        addCardToJob: function(state, job) {
            let pos = state.models.person.values.working_information.jobs.indexOf(job);
            if(pos !== -1) {
                state.models.person.values.working_information.jobs[pos].cards.push({
                    key: Date.now(),
                    number: '',
                    from: '',
                    until: ''
                });
            }
        },
        editCardFromJob: function(state, {job, card, attribute, value}) {
            let pos = state.models.person.values.working_information.jobs.indexOf(job);
            if(pos !== -1) {
                let pos2 = state.models.person.values.working_information.jobs[pos].cards.indexOf(card);
                if(pos2 !== -1) {
                    let ref = state.models.person.values.working_information.jobs[pos].cards[pos2];
                    ref[attribute] = value;
                }
            }
        },
        /**
         * Given a job and a card, removes the card from the card list of the job.
         */
        removeCardFromJob: function(state, {job, card}) {
            let pos = state.models.person.values.working_information.jobs.indexOf(job);
            if(pos !== -1) {
                let pos2 = state.models.person.values.working_information.jobs[pos].cards.indexOf(card);
                if(pos2 !== -1) {
                    state.models.person.values.working_information.jobs[pos].cards.splice(pos2, 1);
                }
            }
        }

    },
    actions: {
        /**
         * UI actions.
         */
        addNotification: function({ commit, state }, { type, message }) {
            let id = state.ui.notifications.next_id;
            commit('addNotification', { id, type, message });
            setTimeout(() => commit('removeNotification', id), 5000);
        },
        /**
         * Lists actions.
         */
        fetchList: function({ commit, state }, what) {
            if(state.debug) console.log('Validating timestamps:', what);
            commit(`updatingList`, { what, value: true });
            axios.get(`/${what}/updated_at`)
            .then(response => {
                let new_timestamp = new Date(response.data.updated_at);
                if(state.lists[what].timestamp === null || state.lists[what].timestamp < new_timestamp) {
                    if(state.debug) console.log('Fetching: ', what);
                    axios.get(`/${what}/list`)
                    .then(response => {
                        if(state.debug) console.log('Fetch success: ', what);
                        commit(`set`, {what, data: response.data, timestamp: new_timestamp});
                    })
                    .catch(error => {
                        if(state.debug) console.log('Fetch failed: ', what, error);
                    })
                    .finally(() => commit(`updatingList`, {what, value: false}));
                }
                else {
                    if(state.debug) console.log('Fetch not needed: ', what);
                    commit(`updatingList`, {what, value: false});
                }
            })
            .catch(error => {
                if(state.debug) console.log('Error while validating timestamps: ', what, error);
            });
        },
        /**
         * Models actions.
         */
        fetchModel: function({ getters, commit, state }, { which, id }) {
            if(state.debug) console.log('Fetching', which, 'id', id);
            let model = getters[which];
            commit('updateModel', { which: which, properties_path: 'updating', value: true });
            commit('updateModel', {which: which, properties_path: 'editing', value: true });
            axios.get(`/${model.plural}/${id}/edit`)
            .then(response => {
                commit('updateModel', {which: which, properties_path: 'id', value: response.data.id });
                commit('updateModel', {which: which, properties_path: 'values', value: response.data.values });
            })
            .catch(error => {
                commit('updateModel', {which: which, properties_path: 'editing', value: false });
                console.log(error);
            })
            .finally(() => commit('updateModel', { which: which, properties_path: 'updating', value: false }));
        },
    }
};
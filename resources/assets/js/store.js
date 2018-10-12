const debug = true;

class List {
    constructor(base_path) {
        this.base_path = base_path;
        this.timestamp = null;
        this.updating  = false;
        this.list      = [];
    }

    getById(id) {
        return this.list.getById(id);
    }

    addItem(item, timestamp) {
        let pos = this.list.getPositionById(item.id);
        if(pos !== undefined) {
            Vue.set(this.list, pos, item);
        }
        else {
            this.list.push(item);
        }
        if(timestamp) {
            this.timestamp = timestamp;
        }
    }

    asOptions(attr = 'name') {
        return this.list.map(item => {
            return {
                id: item.id ? item.id : item[attr],
                text: item[attr],
            };
        });
    }

    forParent(attr, value, strict = true) {
        let new_list = new List();
        new_list.list = this.list.filter(item => {
            let ret = item[attr] === value;
            if(!strict) {
                ret = ret || item[attr] === null;
            }
            return ret;
        });
        return new_list;
    }
}

class StaticList extends List {
    constructor(list) {
        super('');
        this.list = list;
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
            gates:          new List('gates'),
            groups:         new List('groups'),
            people:         new List('people'),
            vehicles:       new List('vehicles'),
            companies:      new List('companies'),
            activities:     new List('activities'),
            // containers:     new List('containers'),
            subactivities:  new List('subactivities'),
            vehicle_types:  new List('vehicle_types'),
            // Locations lists
            homelands:      new List('app/locations/countries'),
        },
        static_lists: {
            risks:          new StaticList([{id: '1', name: 'Nivel 1'}, {id: '2', name: 'Nivel 2'}, {id: '3', name: 'Nivel 3'}]),
            sexes:          new StaticList([{id: 'F', name: 'Femenino'}, {id: 'M', name: 'Masculino'}, {id: 'O', name: 'Otro'}]),
            document_types: new StaticList([{id: "0", name: 'DNI'}, {id: "1", name: 'Pasaporte'}]),
            blood_types:    new StaticList([
                                                {id: "0-", text: "0-"},
                                                {id: "0+", text: "0+"},
                                                {id: "A-", text: "A-"},
                                                {id: "A+", text: "A+"},
                                                {id: "B-", text: "B-"},
                                                {id: "B+", text: "B+"},
                                                {id: "AB-", text: "AB-"},
                                                {id: "AB+", text: "AB+"},   
                                            ]),
        },
        models: {
            group:     new Model('group',       'groups'),
            person:    new Model('person',      'people'),
            vehicle:   new Model('vehicle',     'vehicles'),
            company:   new Model('company',     'companies'),
            container: new Model('container',   'containers'),
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
        containers: function({lists}) {
            return lists.containers;
        },
        companies: function({lists}) {
            return lists.companies;
        },
        activities: function({lists}) {
            return lists.activities;
        },
        subactivities: function({lists}) {
            return lists.subactivities;
        },
        vehicle_types: function({lists}) {
            return lists.vehicle_types;
        },
        groups: function({lists}) {
            return lists.groups;
        },
        gates: function({lists}) {
            return lists.gates;
        },
        homelands: function({lists}) {
            return lists.homelands;
        },
        /**
         * Static lists
         */
        static_lists: function({static_lists}) {
            return static_lists;
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
        },
        container: function({models}) {
            return models.container;
        },
        group: function({models}) {
            return models.group;
        }
    },
    mutations: {
        /**
         * Given a list and an item, if the list doesn't contains and item with the same id of the new item,
         * adds the new item to the list. Otherwise, updates the existing item with the new item data.
         */
        addItemToList: function({debug, lists}, {list, item, timestamp}) {
            if(debug) console.log('Adding to', list, 'the item', item, 'with timestamp', timestamp);
            lists[list].addItem(item, timestamp);
        },

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
         * Given a type and a message, creates and adds a new notification with an unique id to the array of notifications.
         */
        addNotification: function(state, notification) {
            if(state.debug) console.log('Adding notification: ', notification);
            state.ui.notifications.list.push(notification);
            // Increases the id for the next notification. Done here because it can't be done in the 'addNotification' action.
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
        /**
         * Changes the 'updating' status of a given list to the given value.
         */
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
         * Given a model name, resets this model to the default values.
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
        pickTruck: function(state, id) {
            if(state.lists.vehicles.list.getById(id) !== undefined) {
                let pos = state.models.container.values.assign_trucks.trucks_id.indexOf(id);
                if(pos !== -1) {
                    state.models.container.values.assign_trucks.trucks_id.splice(pos, 1);
                }
                else {
                    state.models.container.values.assign_trucks.trucks_id.push(id);
                }
                if(state.debug) console.log('Trcucks picked:', state.models.container.values.assign_trucks.trucks_id);
            }
        },
        pickContainer: function(state, id) {
            if(state.lists.containers.list.getById(id) !== undefined) {
                let pos = state.models.vehicle.values.assign_containers.containers_id.indexOf(id);
                if(pos !== -1) {
                    state.models.vehicle.values.assign_containers.containers_id.splice(pos, 1);
                }
                else {
                    state.models.vehicle.values.assign_containers.containers_id.push(id);
                }
                if(state.debug) console.log('Trcucks picked:', state.models.vehicle.values.assign_containers.containers_id);
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
                art_company: '',
                art_number: '',
                cards: [{ key: Date.now(), number: '', from: '', until: '' }]
            });
        },
        /**
         * Given a job of the jobs list of the person's model, updates its values.
         */
        updateJob: function(state, {job_key, attribute, value}) {
            let pos = state.models.person.values.working_information.jobs.getPositionById(job_key, 'key');
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
        },

        /**
         * Adds a new group to the groups list of the company's model
         */
        addGroup: function(state) {
            state.models.company.values.assign_groups.groups.push({
                key: Date.now(),
                name: '',
                gate_id: '',
                start: '',
                end: ''
            });
        },
        /**
         * Given a group of the groups list of the company's model, updates its values
         */
        updateGroup: function(state, {group_key, attribute, value}) {
            let pos = state.models.company.values.assign_groups.groups.getPositionById(group_key, 'key');
            let ref = state.models.company.values.assign_groups.groups[pos];
            if(state.debug) console.log('Updating model: ', 'company', 'state.models.company.values.assign_groups.groups', value);
            ref[attribute] = value;
        },
        /**
         * Given a group, removes it from the groups list of the company's model
         */
        deleteGroup: function(state, group) {
            let pos = state.models.company.values.assign_groups.groups.indexOf(group);
            if(pos !== -1) {
                state.models.company.values.assign_groups.groups.splice(pos, 1);
            }
        },
    },
    actions: {
        /**
         * Given a type and a message, adds a new notification with this data.
         */
        addNotification: function({ commit, state }, { type, message }) {
            let id = state.ui.notifications.next_id;
            commit('addNotification', { id, type, message });
            setTimeout(() => commit('removeNotification', id), 5000);
        },
        /**
         * Given a list name, validates if the server's data associated with it has changed.
         * If so, then updates the list with the new data, otherwise, leaves the list unchanged.
         */
        fetchList: function({ commit, state }, what) {
            if(state.debug) console.log('Validating timestamps:', what);
            commit(`updatingList`, { what, value: true });
            axios.get(`/${state.lists[what].base_path}/updated-at`)
            .then(response => {
                let new_timestamp = new Date(response.data.updated_at);
                if(state.lists[what].timestamp === null || state.lists[what].timestamp < new_timestamp) {
                    if(state.debug) console.log('Fetching: ', what);
                    axios.get(`/${state.lists[what].base_path}/list`)
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
         * Given a model name and an ID, gets the data associated to this combination from the server.
         */
        fetchModel: function({ getters, commit, state }, { which, id }) {
            if(state.debug) console.log('Fetching', which, 'id', id);
            let model = getters[which];
            commit('updateModel', { which: which, properties_path: 'updating', value: true });
            commit('updateModel', { which: which, properties_path: 'editing', value: true });
            axios.get(`/${model.plural}/${id}/edit`)
            .then(response => {
                console.log(response);
                
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
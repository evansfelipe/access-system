export default {
    default: (debug = false) => { 
        return {
            general_information: {
                company_id: !debug ? '' : 1,
                type_id:    !debug ? '' : 1,
                owner:      !debug ? '' : 'Example Owner Name',
                plate:      !debug ? '' : 'AAE 897',
                brand:      !debug ? '' : 'Chevrolet',
                model:      !debug ? '' : 'Corsa',
                colour:     !debug ? '' : 'Black',
                year:       !debug ? '' : '2010',
                vtv:        !debug ? '' : '2020-01-01',
                insurance:  !debug ? '' : '2020-01-01',
            },
            assign_people: {
                people_id: []
            }
        }
    }
};
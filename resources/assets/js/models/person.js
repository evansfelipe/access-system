export default {
    default: (debug = false) => { 
        return {
            personal_information: {
                picture:         '',
                last_name:       !debug ? '' : 'Example',
                name:            !debug ? '' : 'Example',
                document_type:   !debug ? '' : '1',
                document_number: !debug ? '' : '11111111',                
                cuil:            !debug ? '' : '20-11111111-1',
                birthday:        !debug ? '' : '1995-01-01',
                sex:             !debug ? '' : 'M', 
                blood_type:      !debug ? '' : '0+',
                pna:             !debug ? '' : '0123456789',
                email:           !debug ? '' : 'mail@example.com',
                home_phone:      !debug ? '' : '2231234567',
                mobile_phone:    !debug ? '' : '223123652643',
                fax:             !debug ? '' : '2237654321',
                street:          !debug ? '' : '',
                apartment:       !debug ? '' : '',
                cp:              !debug ? '' : '',
                country:         !debug ? '' : '',
                province:        !debug ? '' : '',
                city:            !debug ? '' : '',
            },
            working_information: {
                risk:            !debug ? '' : '',
                art:             !debug ? '' : '123456789',
                pbip:            !debug ? '' : '2020-01-01',
                jobs: [{
                    key: Date.now(),
                    company_id: '',
                    activity_id: '',
                    subactivities: ['hola'],
                    cards: [{
                        key: Date.now(),
                        number: '',
                        from: '',
                        until: '',
                    }]
                }],
            },
            assign_vehicles: { vehicles_id: [] },
            documentation: { }
        }
    }
};
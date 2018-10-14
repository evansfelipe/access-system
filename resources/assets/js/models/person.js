export default {
    default: (debug = false) => { 
        return {
            personal_information: {
                picture:         !debug ? '' : '',
                last_name:       !debug ? '' : 'Example',
                name:            !debug ? '' : 'Example',
                document_type:   !debug ? '' : '1',
                document_number: !debug ? '' : '11111111',                
                cuil:            !debug ? '' : '20-11111111-1',
                birthday:        !debug ? '' : '1995-01-01',
                sex:             !debug ? '' : 'M', 
                blood_type:      !debug ? '' : '0+',
                pna:             !debug ? '' : '0123456789',
                risk:            !debug ? '' : '1',
                homeland:        !debug ? '' : '',
                register_number: !debug ? '' : '123450999',
                email:           !debug ? '' : 'mail@example.com',
                home_phone:      !debug ? '' : '2231234567',
                mobile_phone:    !debug ? '' : '223123652643',
                fax:             !debug ? '' : '2237654321',
                street:          !debug ? '' : 'Calle falsa',
                apartment:       !debug ? '' : '1º A',
                cp:              !debug ? '' : '123',
                country:         !debug ? '' : '',
                province:        !debug ? '' : '',
                city:            !debug ? '' : '',
            },
            working_information: {
                jobs: [{
                    key: Date.now(),
                    company_id:     !debug ? '' : 5,
                    activity_id:    !debug ? '' : 1,
                    subactivities:  !debug ? [] : ['hola'],
                    groups:         !debug ? [] : [],
                    art_company:    !debug ? '' : 'ART Test',
                    art_number:     !debug ? '' : '123456',
                    company_note: {
                        file: '',
                        name: '',
                        expiration: '2020-06-07'
                    },
                    art_file: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    cards: [{
                        key: Date.now(),
                        number:     !debug ? '' :  Date.now().toString(),
                        from:       !debug ? '' : '2020-01-01',
                        until:      !debug ? '' : '2020-01-02',
                    }]
                }],
            },
            assign_vehicles: { vehicles_id: [] },
            documentation: {
                documents: {
                    dni_copy: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    pna_file: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    driver_license: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    acc_pers: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    boarding_passbook: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    boarding_card: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    health_notebook: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                    pbip_file: {
                        file: '',
                        name: '',
                        expiration: ''
                    },
                },
                documents_required: {
                    acc_pers:           false,
                    art_file:           false,
                    boarding_card:      false,
                    boarding_passbook:  false,
                    company_note:       false,
                    dni_copy:           false,
                    driver_license:     false,
                    health_notebook:    false,
                    pbip_file:          false,
                    pna_file:           false,
                },
            }
        }
    }
};
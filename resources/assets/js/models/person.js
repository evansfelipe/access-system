export default {
    default: (debug = false) => { 
        return {
            id: null,
            personal_information: {
                last_name:       !debug ? '' : 'Example',
                name:            !debug ? '' : 'Example',
                document_type:   !debug ? '' : '1',
                document_number: !debug ? '' : '11111111',                
                cuil:            !debug ? '' : '11111111111',
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
                city:            !debug ? '' : ''
            },
            working_information: {
                company_id:      !debug ? '' : '1',
                activity_id:     !debug ? '' : '1',
                art:             !debug ? '' : '123456789',
                pbip:            !debug ? '' : '2020-01-01'
            },
            assign_vehicles: {
            },
            first_card: {
                number:          !debug ? '' : '918273645',
                risk:            !debug ? '' : '1',
                from:            !debug ? '' : '2020-01-01',
                until:           !debug ? '' : '2020-01-02'
            },
            documentation: {
            }
        }
    }
};
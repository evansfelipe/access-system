export default {
    default: (debug = false) => { 
        return {
            general_information: {
                business_name:  !debug ? '' : 'Example company S.A.',
                name:           !debug ? '' : 'Example company',
                area:           !debug ? '' : 'Example area',
                cuit:           !debug ? '' : '20-14671253-4',
                expiration:     !debug ? '' : '2020-01-01',
                phone:          !debug ? '' : '4880000',
                fax:            !debug ? '' : '4881111',
                email:          !debug ? '' : 'example@company.com',
                web:            !debug ? '' : 'www.company.com',
                street:         !debug ? '' : 'Example street',
                apartment:      !debug ? '' : '2º B',
                cp:             !debug ? '' : '7600',
                country:        !debug ? '' : '',
                province:       !debug ? '' : '',
                city:           !debug ? '' : ''
            },
            assign_groups: {
                groups: !debug ? [] : [{
                    key:        'T' + Date.now(),
                    name:       !debug ? '' : 'Example group name',
                    zone_id:    !debug ? '' : 1,
                    start:      !debug ? '' : '09:00',
                    end:        !debug ? '' : '17:00',
                    days: {
                        monday:    false,
                        tuesday:   false,
                        wednesday: false,
                        thursday:  false,
                        friday:    false,
                        saturday:  false,
                        sunday:    false,
                    }
                }]
            }
        }
    }
};
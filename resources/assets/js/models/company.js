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
                apartment:      !debug ? '' : '',
                cp:             !debug ? '' : '8888',
                country:        !debug ? '' : '',
                province:       !debug ? '' : '',
                city:           !debug ? '' : ''
            }
        }
    }
};
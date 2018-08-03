export default {
    default: (debug = false) => { 
        return {
            general_information: {
                name:       !debug ? '' : 'Example company',
                area:       !debug ? '' : 'Example area',
                cuit:       !debug ? '' : '0123456789',
                expiration: !debug ? '' : '2020-01-01',
                phone:      !debug ? '' : '4880000',
                fax:        !debug ? '' : '4881111',
                email:      !debug ? '' : 'example@company.com',
                web:        !debug ? '' : 'www.company.com',
                street:     !debug ? '' : 'Example street',
                apartment:  !debug ? '' : '',
                cp:         !debug ? '' : '8888',
                country:    !debug ? '' : '',
                province:   !debug ? '' : '',
                city:       !debug ? '' : ''
            }
        }
    }
};
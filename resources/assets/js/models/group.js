export default {
    default: (debug = false) => { 
        return {
            general_information: {
                name:       !debug ? '' : 'Example group name',
                company_id: !debug ? '' : 1,
                gate_id:    !debug ? '' : 1,
                start:      !debug ? '' : '09:00',
                end:        !debug ? '' : '17:00'
            },
        }
    }
};
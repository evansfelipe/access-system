export default {
    default: (debug = false) => { 
        return {
            general_information: {
                company_id: !debug ? '' : '',
                name:       !debug ? '' : '',
                gate_id:    !debug ? '' : '',
                start:      !debug ? '' : '',
                end:        !debug ? '' : '',
            },
        }
    }
};
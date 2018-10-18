export default {
    default: (debug = false) => { 
        return {
            general_information: {
                name:          !debug ? '' : 'Example group name',
                company_id:    !debug ? '' : 1,
                zone_id:       !debug ? '' : 1,
                start:         !debug ? '' : '09:00',
                end:           !debug ? '' : '17:00',
                days: {
                    monday:    false,
                    tuesday:   false,
                    wednesday: false,
                    thursday:  false,
                    friday:    false,
                    saturday:  false,
                    sunday:    false,
                }
            },
        }
    }
};
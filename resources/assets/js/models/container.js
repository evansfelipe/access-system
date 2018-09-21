export default {
    default: (debug = false) => { 
        return {
            general_information: {
                series_number:  !debug ? '' : '154788',
                format:         !debug ? '' : 'Format example',
                brand:          !debug ? '' : 'Brand example',
                model:          !debug ? '' : 'Model example',
                size:           !debug ? '' : '20x10x5',
                colour:         !debug ? '' : 'Black',
            },
            assign_trucks: {
                trucks_id: []
            }
        }
    }
};
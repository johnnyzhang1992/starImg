/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-05 19:16
 */
let initState = {
    current_page: 1,
    last_page: 1,
    stars: [],
    show_spinner: false,
    status: 'LOADING'
};

export default (state = initState,action) =>{
    // console.log(action);
    switch(action.type) {
        case 'FETCH_STARTED': {
            return {...state,status: 'LOADING'};
        }
        case 'FETCH_SUCCESS': {
            return {
                current_page: action.result.current_page,
                last_page:action.result.last_page,
                status: 'SUCCESS',
                stars: [...state.stars,...action.result.stars],
                show_spinner: action.show_spinner
            };
        }
        case 'FETCH_FAILURE': {
            return {...state,status: 'FAILURE'};
        }
        default: {
            return state;
        }
    }
};
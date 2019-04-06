import axios from "axios";

/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-05 19:23
 */
// let current_page = 0;

// export const getMoreStar = (last_page,csrfToken,show_spinner)=>({
//     type: 'REQUEST_MORE_STAR',
//     page: current_page+1,
//     last_page: last_page ? last_page : 1,
//     csrfToken: csrfToken,
//     show_spinner: show_spinner ? show_spinner : false,
//     receivedAt: new Date()
// });

export const receiveMoreStar = ()=>({
    type: 'RECEIVE_MORE_STAR',
    receivedAt: new Date()
});

export const fetchStarted = () => ({
    type: 'FETCH_STARTED',
    show_spinner: true

});

export const fetchSuccess = (result) => ({
    type: 'FETCH_SUCCESS',
    show_spinner: false,
    result
});

export const fetchFailure = (error) => ({
    type: 'FETCH_FAILURE',
    show_spinner:true,
    error
});

export const fetchMoreStars = (current_page,last_page,crsfToken)=>{
    return (dispatch) => {

        const page = current_page+1;
        // current_page++;

        const dispatchIfValid = (action) => {
            if (page <= last_page) {
                return dispatch(action);
            }
        };

        // dispatchIfValid(fetchStarted());

        // console.log(page,last_page);
        if(page<=last_page){
            console.log('-----reducer--update--');
            let result = {};
            return axios.post('/starList?page='+page, {
                params: {
                    'csrf-token': crsfToken
                }
            }).then((res) => {
                console.log(`-----loading stars----${page}`);
                result = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    stars: res.data.data
                };
                dispatchIfValid(fetchSuccess(result));
            }).catch((error) => {
                console.log(error);
                dispatchIfValid(fetchFailure(error));
            });
        }else{
            // dispatchIfValid(fetchSuccess(null));
            return new Promise((resolve)=>{
                resolve({});
            })
        }

    }
};
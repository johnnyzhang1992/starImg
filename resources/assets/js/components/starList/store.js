/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-05 19:16
 */
import {createStore,applyMiddleware} from 'redux';
import thunk from 'redux-thunk'
import reducer from './reducer';

export default createStore(reducer,applyMiddleware(thunk));
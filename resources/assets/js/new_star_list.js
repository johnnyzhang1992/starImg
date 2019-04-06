/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-05 22:01
 */
import React,{Component} from 'react';
import ReactDOM from "react-dom";
import {Provider} from 'react-redux';
import StarList from './components/starList/views/starList';
import Header from './components/header';
import store from './components/starList/store.js';


class App extends Component{
    constructor(props){
        super(props);
    }
    render(){
        return (
            <Provider store={store}>
                <Header />
                <StarList />
            </Provider>
        )
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
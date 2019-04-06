/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-05 19:21
 */
import React, { Component } from 'react';
import { Masonry,Box,Spinner} from 'gestalt';
import StarItem from './starItem';
import * as Until from '../../../untils/until';
import store from '../store';
import * as actions from "../actions";


class StarList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            current_page:0,
            stars: [],
            show_spinner:true,
            last_page: 1,
            status: 'LOADING'
        };
        this.handleScroll = this.handleScroll.bind(this)
    }
    shouldComponentUpdate(nextProps, nextState, nextContext) {
        return nextState.stars !==this.state.stars;
    }

    // 在第一次渲染后调用，只在客户端。
    // 你应该在 componentDidMount 生命周期方法内发送 AJAX 请求数据。
    // 这样你才能够在请求的数据到达时使用 setState 更新你的组件。
    componentDidMount() {
        let crsfToken = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');
        store.dispatch(actions.fetchMoreStars(this.state.current_page,this.state.last_page,crsfToken,!this.state.show_spinner)).then((res) => {
            let new_state =store.getState();
            this.setState({
                current_page: new_state['current_page'],
                stars: new_state['stars'],
                last_page: new_state['last_page'],
                show_spinner: new_state['show_spinner'],
                status: new_state['status']
            });
        });
        window.addEventListener('scroll', () => {
            this.handleScroll(this);
        });
        store.subscribe(this.handleScroll);
    }
    // 距离底部30px时，加载更多内容
    handleScroll() {
        // let _this = this;
        let scrollTop = Until.getScrollTop();
        let scrollHeight = Until.getScrollHeight();
        let windowHeight = Until.getWindowHeight();
        if((scrollTop + windowHeight+ 30 > scrollHeight) && this.state.status!=='LOADING'){
            this.setState({
                status: 'LOADING'
            });
            let crsfToken = document.getElementsByTagName('meta')['csrf-token'].getAttribute('content');
            store.dispatch(actions.fetchMoreStars(this.state.current_page,this.state.last_page,crsfToken,!this.state.show_spinner)).then((res) => {
                let new_state =store.getState();
                this.setState({
                    current_page: new_state['current_page'],
                    stars: new_state['stars'],
                    last_page: new_state['last_page'],
                    show_spinner: new_state['show_spinner'],
                    status: new_state['status']
                });
            });
        }
    }
    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {
        window.removeEventListener('scroll', () => {
            this.handleScroll();
        });
        // store.unsubscribe(this.handleScroll);
    }
    render() {
        return (
            <Box paddingY={6}>
                <div className="gridCentered">
                    <Masonry
                        comp={StarItem}
                        items={this.state.stars}
                        loadItems={(event)=>{}}
                        minCols={2}
                        gutterWidth = {5}
                        flexible = {true}
                    />
                    <Box marginBottom={6}>
                        <Spinner accessibilityLabel={'Load more Pins'} show={this.state.show_spinner}/>
                    </Box>
                </div>
            </Box>
        );
    }
}

export default StarList;
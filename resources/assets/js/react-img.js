// import 'babel-polyfill';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import { Masonry,Box,Spinner} from 'gestalt';
import Pin from './components/pin';
import Header from './components/header';
// import { Button } from 'gestalt';
import * as until from './untils/until'
let timeOut = null;
class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            // pins: items,
            is_load:true,
            pins: [],
            last_page:1,
            show_spinner:false,
            current_page:0,
            url: 'https://starimg.cn/getImages'
        };
    }
    // 在第一次渲染后调用，只在客户端。
    // 你应该在 componentDidMount 生命周期方法内发送 AJAX 请求数据。
    // 这样你才能够在请求的数据到达时使用 setState 更新你的组件。
    componentDidMount() {
        this.getPins(this);
        let _this = this;
        window.addEventListener('scroll', () => {
            _this.handleScroll(_this);
        });
    }
    // 距离底部30px时，加载更多内容
    handleScroll(th) {
        let scrollTop = until.getScrollTop();
        let scrollHeight = until.getScrollHeight();
        let windowHeight = until.getWindowHeight();
        if(scrollTop + windowHeight+ 30 > scrollHeight){
          this.getPins(th);
        }
    }
    // 在组件完成更新后立即调用。在初始化时不会被调用
    componentDidUpdate(){
        timeOut = setTimeout(()=>{
            this.setState({
                show_spinner: false
            });
            clearTimeout(timeOut)
        },2000)
    }
    // 获取 pins 数据
    getPins(th){
        let that = th;
        th.setState({
            show_spinner: true
        });
        let page = that.state.current_page+1;
        if((page<that.state.last_page && that.state.is_load) || page==1){
            that.setState({
                is_load: false
            });
            axios.get(th.state.url+'?page='+page, {
                params:{
                    'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
                }
            }).then((res)=>{
                that.setState({
                    pins: that.state.pins.concat(res.data.data),
                    is_load: res.data.next_page_url,
                    last_page: res.data.last_page,
                    current_page: res.data.current_page,
                });
            }).catch((error)=>{
                console.log(error);
            });
        }else{
            this.setState({
                show_spinner: false
            })
        }
    }
    render() {
        return (
            <div>
                <Header/>
                <div className="gridCentered">
                    <Masonry
                        comp={Pin}
                        items={this.state.pins}
                        loadItems={(event)=>{}}
                        minCols={2}
                        gutterWidth = {5}
                        flexible = {true}
                    />
                    <Box marginBottom={6}>
                        <Spinner accessibilityLabel={'Load more Pins'} show={this.state.show_spinner}/>
                    </Box>
                </div>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import { Masonry } from 'gestalt';
import { Box } from 'gestalt';
import { Spinner } from 'gestalt';
import Pin from './components/pin';
import Header from './components/header';
// import { Button } from 'gestalt';

//滚动条在Y轴上的滚动距离
function getScrollTop(){
    let scrollTop = 0, bodyScrollTop = 0, documentScrollTop = 0;
    if(document.body){
        bodyScrollTop = document.body.scrollTop;
    }
    if(document.documentElement){
        documentScrollTop = document.documentElement.scrollTop;
    }
    scrollTop = (bodyScrollTop - documentScrollTop > 0) ? bodyScrollTop : documentScrollTop;
    return scrollTop;
}
//文档的总高度
function getScrollHeight(){
    let scrollHeight = 0, bodyScrollHeight = 0, documentScrollHeight = 0;
    if(document.body){
        bodyScrollHeight = document.body.scrollHeight;
    }
    if(document.documentElement){
        documentScrollHeight = document.documentElement.scrollHeight;
    }
    scrollHeight = (bodyScrollHeight - documentScrollHeight > 0) ? bodyScrollHeight : documentScrollHeight;
    return scrollHeight;
}
//浏览器视口的高度
function getWindowHeight(){
    let windowHeight = 0;
    if(document.compatMode == "CSS1Compat"){
        windowHeight = document.documentElement.clientHeight;
    }else{
        windowHeight = document.body.clientHeight;
    }
    return windowHeight;
}
class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            // pins: items,
            is_load:true,
            count: 16,
            total: 0,
            pins: [],
            show_spinner:false,
            current_page:0,
            url: window.location.href+'/getImages'
        };
    }
    // 在第一次渲染后调用，只在客户端。
    // 你应该在 componentDidMount 生命周期方法内发送 AJAX 请求数据。
    // 这样你才能够在请求的数据到达时使用 setState 更新你的组件。
    componentDidMount() {

    }
    // 距离底部30px时，加载更多内容
    handleScroll(th) {
        let scrollTop = getScrollTop();
        let scrollHeight = getScrollHeight();
        let windowHeight = getWindowHeight();
        if(scrollTop + windowHeight+ 30 > scrollHeight){
          this.getPins(th);
        }
    }
    // 在组件接收到新的props或者state但还没有render时被调用。在初始化时不会被调用。
    componentWillUpdate(){

    }
    // 在组件完成更新后立即调用。在初始化时不会被调用
    componentDidUpdate(){
        setTimeout(()=>{
            this.setState({
                show_spinner: false
            })
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
                    total: res.data.total,
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
    // 在渲染前调用,在客户端也在服务端。
    componentWillMount() {
        this.getPins(this);
        let _this = this;
        window.addEventListener('scroll', () => {
            _this.handleScroll(_this);
        });
    }
    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

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
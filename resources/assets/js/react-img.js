import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import { Text } from 'gestalt';
import { Masonry } from 'gestalt';
import { Image } from 'gestalt';
import { Card } from 'gestalt';
import { Box } from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
import { Icon } from 'gestalt';
import { Spinner } from 'gestalt';
// import { Button } from 'gestalt';

// box color list
const boxColor = ["blue","darkGray","darkWash","eggplant","gray","green","lightGray","lightWash","maroon","midnight","navy","olive","orange","orchid","pine","purple","red","transparent","watermelon","white"];
const imageColor = ['#7a4579','#d56073','#ec9e69',"#bad7df",'#ffe2e2','#f6f6f6','#99ddcc','#dd0a35','#e4d1d3','#1687a7','#014955','#feb062','#575151','#3f3b3b'];
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

class Pin extends Component {
    constructor(props) {
        super(props);
        this.state = {
            item: props.data,
            itemIdx: props.itemIdx >10 ? parseInt(props.itemIdx % 10) : props.itemIdx,
            boxColor: boxColor,
            imageColor:imageColor,
            clientWidth: document.documentElement.clientWidth,
            style: {
                clear:'both',
                overflow:'hidden',
                width:'100%',
            },
            hovered: false
        };
    }

    // 在第一次渲染后调用，只在客户端。之后组件已经生成了对应的DOM结构，可以通过this.getDOMNode()来进行访问。
    componentDidMount() {

    }

    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

    }
    render() {
        return (
            <div style={this.state.style} className={'pinItem'}>
                <Box paddingX={2}  shape={'square'}>
                    <Card
                        paddingX={3}
                        paddingY={2}
                        onMouseEnter={()=>{
                            this.setState({
                                hovered: this.state.clientWidth > 768
                            })
                        }}
                        onMouseLeave={()=>{
                            this.setState({ hovered: false });
                        }}>
                        <Box shape={'roundedTop'} color={this.state.boxColor[this.state.itemIdx]}>
                            <Image
                                alt={this.state.item.text}
                                // fit="cover"
                                color = {this.state.imageColor[this.state.itemIdx]}
                                naturalWidth={this.state.item.pic_detail  ? this.state.item.pic_detail.geo.width : 360   }
                                naturalHeight={this.state.item.pic_detail  ? this.state.item.pic_detail.geo.height : 540}
                                src={this.state.item.pic_detail ? this.state.item.pic_detail.url :this.state.item.display_url}
                                >
                                <Box paddingX={3} paddingY={1} position={'absolute'} bottom={true} left={true} shape={'rounded'} color={'white'} marginLeft={3} marginBottom={3} display={this.state.hovered ? 'block' : 'none'}>
                                    <Link href={this.state.item.origin_url}>
                                        <Box alignItems="center" display="flex">
                                            <Box marginRight={1} padding={1}>
                                                <Icon icon="arrow-up-right" accessibilityLabel="link" color="darkGray" inline={true}/>
                                            </Box>
                                            <Text align="center" bold color="darkGray">
                                                weibo.com
                                            </Text>
                                        </Box>
                                    </Link>
                                </Box>
                            </Image>
                        </Box>
                        <Box display="flex" direction="row" paddingY={2}>
                            <Box column={2} paddingY={2}>
                                <Link href={'weibo.com/u/'+this.state.item.wb_id} target={'blank'}>
                                    <Avatar name={this.state.item.screen_name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                </Link>
                            </Box>
                            <Box column={10} padding={2}>
                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                    <Link href={'weibo.com/u/'+this.state.item.wb_id} target={'blank'}>{this.state.item.screen_name}</Link>
                                </Text>
                            </Box>
                        </Box>
                    </Card>
                </Box>
            </div>
        );
    }
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
            current_page:0
        };
    }
    // 在第一次渲染后调用，只在客户端。
    // 你应该在 componentDidMount 生命周期方法内发送 AJAX 请求数据。
    // 这样你才能够在请求的数据到达时使用 setState 更新你的组件。
    componentDidMount() {

    }
    handleScroll(th) {
        let scrollTop = getScrollTop();
        let scrollHeight = getScrollHeight();
        let windowHeight = getWindowHeight();
        if(scrollTop + windowHeight+ 30 > scrollHeight){
          this.getPins(th);
        }
    }
    getPins(th){
        let that = th;
        that.setState({
            show_spinner: true
        });
        let page = that.state.current_page+1;
        if((page<that.state.last_page && that.state.is_load) || page==1){
            that.setState({
                is_load: false
            });
            axios.get('/getImages?page='+page, {
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
                setTimeout(()=>{
                    that.setState({
                        show_spinner: false
                    })
                },3500)
            }).catch((error)=>{
                console.log(error);
            });
        }else{
            this.setState({
                show_spinner: false
            })
        }
    }
    componentWillMount() {
        this.getPins(this);
        let _this = this;
        window.addEventListener('scroll', () => {
            _this.handleScroll(_this);
        });
    }
    componentWillUnmount() {

    }
    render() {
        return (
            <div className="App">
                <Box color="white" shape="rounded" padding={3}>
                    <Link href="/">
                        <Box padding={2}>
                            <Text bold>starImg</Text>
                        </Box>
                    </Link>
                </Box>
                <Masonry
                    comp={Pin}
                    items={this.state.pins}
                    loadItems={(event)=>{}}
                    minCols={2}
                    gutterWidth = {5}
                    flexible = {true}
                />
                <Spinner accessibilityLabel={'Load more Pins'} show={this.state.show_spinner}/>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
import 'babel-polyfill';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import { Masonry } from 'gestalt';
import { Box } from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
import { Icon } from 'gestalt';
import { Spinner } from 'gestalt';
import { Column } from 'gestalt';
import { Text } from 'gestalt';
import { Flyout } from 'gestalt';
import { Button } from 'gestalt';
import { Divider } from 'gestalt';
import { SegmentedControl } from 'gestalt';
import Pin from './components/pin';
import Header from "./components/header";
import FontAwesomeIcon from 'react-fontawesome'


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
            clientWidth: document.documentElement.clientWidth,
            show_spinner:true,
            current_page:0,
            url: window.location.href+'/getImages',
            star: [],
            itemIndex: 0,
            open: false,
            type_name: '微博图片',
            sort_by: 'time'
        };
        this.handleItemChange = this.handleItemChange.bind(this);
        this.handleClick = this._handleClick.bind(this);
        this.handleDismiss = this._handleDismiss.bind(this);
        this.handleSortByTime = this.handleSortByTime.bind(this);
        this.handleSortByLikeCount = this.handleSortByLikeCount.bind(this);
    }
    handleItemChange({ activeIndex }) {
        let type_name = '微博图片';
        switch (activeIndex) {
            case 0:
                // console.log('weibo');
                type_name = '微博图片';
                break;
            case 1:
                type_name = 'Ins 图片';
                // console.log('ins');
                break;
            case 2:
                type_name = '其他图片';
                // console.log('others');
                break;
            default:
                console.log('default');
        }
        this.setState(prevState => ({
            itemIndex: activeIndex ,
            type_name: type_name
        }));
        this.getPins(this,1,'time',activeIndex);
    }
    _handleClick() {
        this.setState(() => ({
            open: !this.state.open
        }));
    }
    _handleDismiss() {
        this.setState(() => ({ open: false }));
    }
    handleSortByTime() {
        this.setState(()=>({
            open: false,
            sort_by: 'time',
            current_page: 0
        }));
        this.getPins(this,1,'time');
    }
    handleSortByLikeCount() {
        this.setState(()=>({
            open: false,
            sort_by: 'like',
            current_page: 0
        }));
        this.getPins(this,1,'like');
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
    getPins(th,_page,sort,index){
        let that = th;
        th.setState({
            show_spinner: true
        });
        let page = _page && _page>0 ? 1: that.state.current_page+1;
        if((page<that.state.last_page && that.state.is_load) || page==1){
            that.setState({
                is_load: false
            });
            axios.get(th.state.url, {
                params:{
                    'page': page,
                    'type': index || index == 0 ? index : that.state.itemIndex,
                    'sort': sort ? sort : that.state.sort_by,
                    'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
                }
            }).then((res)=>{
                let pins = res.data.data;
                pins.forEach((item)=>{
                   if(item.origin != '微博'){
                       if(item.cos_url){
                           return item;
                       }
                   }
                    return item;
                });
                that.setState({
                    total: res.data.total,
                    pins: _page && _page>0 ? pins :that.state.pins.concat(pins),
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

    getStarDetail(th){
        let that = th;
        axios.post(window.location.href, {
            params:{
                'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
            }
        }).then((res)=>{
            // console.log(res.data);
            that.setState({
                star: res.data.star
            });
        }).catch((error)=>{
            console.log(error);
        });
    }
    // 在渲染前调用,在客户端也在服务端。
    componentWillMount() {
        this.getStarDetail(this);
        setTimeout(()=>{
            this.getPins(this);
        },3000);
        let _this = this;
        window.addEventListener('scroll', () => {
            _this.handleScroll(_this);
        });
    }
    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

    }
    render() {
        const items = [
            '微博',
            'Ins',
            '其他'
        ];
        return (
            <div>
                <Header/>
                <Box display="flex" direction="row">
                    <Column span={this.state.clientWidth >768 ? 1 : 0} > </Column>
                    <Column span={this.state.clientWidth >768 ? 10 : 12} >
                        <Box display="flex" direction="row" paddingX={8} paddingY={2}>
                            <Column span={this.state.clientWidth >768 ? 6 : 3} >
                                <Box color="white" paddingX={5} paddingY={3} display={'flex'} direction={'column'} alignSelf={'end'} alignItems={'end'}>
                                    <Box color="white" paddingY={2} width={this.state.clientWidth >768 ? 106 : 50} alignContent={'end'} alignSelf={'end'} alignItems={'end'} display={'flex'}>
                                        <Avatar name={'User name'} src={this.state.star.avatar } verified={this.state.star.verified}/>
                                    </Box>
                                </Box>
                            </Column>
                            <Column span={this.state.clientWidth >768 ? 5: 9}>
                                <Box color="white" paddingX={5} paddingY={3}>
                                    <Box color="white" paddingY={2}>
                                        <Text align={'left'}>{this.state.star.name}</Text>
                                    </Box>
                                    <Box color="white">
                                        <Text align={'left'} size={'xs'} color={'gray'}>{this.state.star.verified ? this.state.star.verified_reason : ''}</Text>
                                    </Box>
                                    <Box color="white" paddingY={1}>
                                        <Text align={'left'} inline={true} bold={true}>{this.state.star.posts_count}</Text>
                                        <Text align={'left'} inline={true}> posts</Text>
                                    </Box>
                                    <Box color="white" paddingY={1} display={this.state.star.baike && this.state.star.baike !='' ? 'block' : 'none'}>
                                        <Text align={'left'} inline={true} color={'gray'}>百度人物资料 </Text>
                                        <Text align="left" inline={true}>{this.state.star.description} </Text>
                                        <Text align={'left'} inline={true} color={'orange'}>
                                            <Link inline={true} href={this.state.star.baike} target={'blank'}>详情</Link>
                                        </Text>
                                    </Box>
                                    <Box color="white" paddingY={2} display={this.state.star.baike && this.state.star.baike !='' ? 'none' : 'block'}>
                                        <Text align="left" >{this.state.star.description}</Text>
                                    </Box>
                                    <Box color="white" paddingY={2} alignSelf={'center'}>
                                        <Box width={24} display={ 'inlineBlock'}>
                                            <Link href={'https://weibo.com/'+(this.state.star.wb_domain ? this.state.star.wb_domain : 'u/'+this.state.star.wb_id)} target={'blank'}>
                                                <FontAwesomeIcon
                                                    className={'f-brand'}
                                                    name={'weibo'}
                                                    size={'2x'}
                                                />
                                                {/*<Avatar name={'Weibo'} />*/}
                                            </Link>
                                        </Box>
                                        <Box width={24} display={this.state.star.ins_name? 'inlineBlock' : 'none'} marginLeft={2}>
                                            <Link href={'https://instagram.com/'+(this.state.star.ins_name )} target={'blank'}>
                                                {/*<Avatar name={'Instagram'} />*/}
                                                <FontAwesomeIcon
                                                    className={'f-brand'}
                                                    name={'instagram'}
                                                    size={'2x'}
                                                />
                                            </Link>
                                        </Box>
                                    </Box>
                                </Box>
                            </Column>
                        </Box>
                        {/*tabs*/}
                        <Box display="flex" direction="row" paddingX={this.state.clientWidth >768 ? 8 : 0}>
                            <Column span={this.state.clientWidth >768 ? 2 : 0} > </Column>
                            <Column span={this.state.clientWidth >768 ? 9 : 12} >
                                <Box color={"white"} paddingY={2} wrap paddingX={5}>
                                    <SegmentedControl
                                        items={items}
                                        selectedItemIndex={this.state.itemIndex}
                                        onChange={this.handleItemChange}
                                    />
                                </Box>
                                <Box color={"white"} wrap paddingY={2} paddingX={8}>
                                    <Box display={'inlineBlock'} height={'36px'}>
                                        <div className={'sortLeft'}>
                                            <Text align={'left'} color={'gray'}>{this.state.type_name} {this.state.total} posts</Text>
                                        </div>
                                    </Box>
                                    <div
                                        className={'sortRight'}
                                        style={{ display: "inline-block" ,float: 'right'}}
                                        ref={c => {
                                            this.anchor = c;
                                        }}
                                    >
                                        <Button
                                            accessibilityExpanded={!!this.state.open}
                                            accessibilityHaspopup
                                            onClick={this.handleClick}
                                            text={this.state.sort_by == 'time' ? '按时间排序': '按热度排序' }
                                            size={'sm'}
                                            color={'white'}
                                        />
                                    </div>
                                    {this.state.open && (
                                        <div className={'sortLayer'}>
                                            <Flyout
                                                anchor={this.anchor}
                                                idealDirection="down"
                                                onDismiss={this.handleDismiss}
                                                size={'xs'}
                                            >
                                                <Box width={'100%'} paddingY={1}>
                                                    <Box>
                                                        <Button
                                                            accessibilityExpanded={!!this.state.open}
                                                            accessibilityHaspopup
                                                            onClick={this.handleSortByTime}
                                                            text={'按时间排序'}
                                                            size={'sm'}
                                                            color={'white'}
                                                        />
                                                    </Box>
                                                    <Box>
                                                        <Button
                                                            accessibilityExpanded={!!this.state.open}
                                                            accessibilityHaspopup
                                                            onClick={this.handleSortByLikeCount}
                                                            text={'按热度排序'}
                                                            size={'sm'}
                                                            color={'white'}
                                                        />
                                                    </Box>
                                                </Box>
                                            </Flyout>
                                        </div>
                                    )}
                                    <Box paddingY={1}>
                                        <Divider />
                                    </Box>
                                </Box>
                            </Column>
                        </Box>
                    </Column>
                </Box>
                <Box paddingY={6}>
                    <div className="gridCentered">
                        {this.state.pins.length>0 ?
                            <Masonry
                                comp={Pin}
                                items={this.state.pins}
                                loadItems={(event)=>{}}
                                minCols={2}
                                gutterWidth = {5}
                                flexible = {true}
                            />
                            :
                            <Text align={'center'}>该分类下暂时无图片</Text>
                        }
                        <Box marginBottom={6}>
                            <Spinner accessibilityLabel={'Load more Pins'} show={this.state.show_spinner}/>
                        </Box>
                    </div>
                </Box>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
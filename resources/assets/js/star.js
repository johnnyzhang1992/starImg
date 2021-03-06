// import 'babel-polyfill';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import { Masonry,Box,Spinner,Column,Text,Flyout,Button,Divider,SegmentedControl} from 'gestalt';
import Pin from './components/pin';
import Header from "./components/header";
import StarHeader from './components/star_header'
// import FontAwesomeIcon from 'react-fontawesome'

import * as until from './untils/until'

// import { Button } from 'gestalt';
let pre_page = 0;
let pre_index = 0;
let is_loading = false;
class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            // pins: items,
            is_load:true,
            pins: [],
            clientWidth: document.documentElement.clientWidth,
            show_spinner:true,
            current_page:0,
            url: window.location.href+'/getImages',
            star: {},
            itemIndex: 1,
            open: false,
            type_name: 'Ins 图片',
            sort_by: 'time_desc',
            wb_count:0,
            ins_count: 0
        };
        this.handleItemChange = this.handleItemChange.bind(this);
        this.handleClick = this._handleClick.bind(this);
        this.handleDismiss = this._handleDismiss.bind(this);
        this.handleSortByTime = this.handleSortByTime.bind(this);
        this.handleSortByLikeCount = this.handleSortByLikeCount.bind(this);
    }
    handleItemChange({ activeIndex }) {
        let type_name = 'Ins 图片';
        let count = 0;
        switch (activeIndex) {
            case 0:
                // console.log('weibo');
                type_name = '微博图片';
                count = this.state.wb_count;
                break;
            case 1:
                type_name = 'Ins 图片';
                count = this.state.ins_count;
                // console.log('ins');
                break;
            case 2:
                type_name = '其他图片';
                count = 0;
                // console.log('others');
                break;
            default:
                count = 0;
                console.log('default');
        }
        if(pre_index !== activeIndex){
            this.setState(prevState => ({
                itemIndex: activeIndex ,
                type_name: type_name,
                total: count,
                pins: [],
                current_page: 1
            }));
            this.getPins(this,1,this.state.sort_by,activeIndex);
        }
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
        let time_sort = this.state.sort_by == 'time_desc' ? 'time_asc' : 'time_desc';
        this.setState((preState)=>({
            open: false,
            sort_by: time_sort,
            current_page: 0
        }));
        this.getPins(this,1,time_sort);
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
        this.getStarDetail(this);
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
        if((scrollTop + windowHeight+ 30 > scrollHeight) && !is_loading){
            this.getPins(th);
        }
    }
    // 在组件完成更新后立即调用。在初始化时不会被调用
    componentDidUpdate(){
        const time_out = setTimeout(()=>{
            this.setState({
                show_spinner: false
            });
            clearTimeout(time_out)
        },2000)
    }
    // 获取 pins 数据
    getPins(th,_page,sort,index){
        let that = th;
        this.setState((preState)=>({
            show_spinner: !preState.show_spinner
        }));
        let _index = index===0 || index === 1 ? index : that.state.itemIndex;
        let page = (_page && _page< 2) || pre_index !== _index ? 1: that.state.current_page+1;
        if(pre_page === page && pre_index === _index){
            return false;
        }
        if((page<=that.state.last_page && that.state.is_load) || page===1){
            that.setState({
                is_load: false
            });
            pre_page = page;
            pre_index = _index;
            is_loading = true;
            axios.get(th.state.url, {
                params:{
                    'page': page,
                    'type': _index,
                    'sort': sort ? sort : that.state.sort_by,
                    'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
                }
            }).then((res)=>{
                let pins = res.data.data;
                if(_index ===1){
                    pins = pins.filter(item=>{
                        return item.cos_url;
                    })
                }
                that.setState({
                    pins: that.state.pins.concat(pins),
                    is_load: res.data.next_page_url,
                    last_page: res.data.last_page,
                    current_page: res.data.current_page,
                    total: res.data.total,
                    itemIndex: _index
                });
                is_loading = false;
            }).catch((error)=>{
                console.log(error);
                is_loading = false;
            });
        }else{
            this.setState((preState)=>({
                show_spinner: !preState.show_spinner
            }));
            is_loading = false;
        }
    }

    getStarDetail(th){
        let that = th;
        is_loading = true;
        axios.post(window.location.href, {
            params:{
                'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
            }
        }).then((res)=>{
            // console.log(res.data);
            that.setState({
                star: res.data.star,
                wb_count: res.data.wb_count,
                ins_count: res.data.ins_count,
                total: res.data.ins_count
            });
            if(res.data.ins_count<1){
                that.setState({
                    itemIndex: 0,
                    type_name: '微博 图片',
                });
                that.getPins(that,1,'time_desc',0);
            }else{
                that.getPins(that,1,'time_desc',1);
            }
            is_loading = false;
        }).catch((error)=>{
            console.log(error);
            is_loading = false;
        });
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
                        <StarHeader {...this.state.star} clientWidth={this.state.clientWidth} />
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
                                            text={this.state.sort_by == 'like' ? '按热度排序': '按时间排序' }
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
                        {this.state.total>0 ?
                            <Masonry
                                comp={Pin}
                                items={this.state.pins}
                                loadItems={(event)=>{}}
                                minCols={2}
                                gutterWidth = {5}
                                flexible = {true}
                            />
                            : (!this.state.show_spinner && this.state.total<1 ?
                                <Text align={'center'}>该分类下暂时无图片</Text>
                                : '')
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
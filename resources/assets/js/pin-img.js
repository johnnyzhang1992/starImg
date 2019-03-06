import 'babel-polyfill';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from  'axios';
import {Box, Icon, IconButton, Image} from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
// import { IconButton } from 'gestalt';
import { Column } from 'gestalt';
import { Text } from 'gestalt';
import Header from "./components/header";


class App extends Component {
    constructor(props) {
        super(props);
        this.handleToggle = this._handleToggle.bind(this);
        this.state = {
            clientWidth: document.documentElement.clientWidth,
            pin: [],
        };
    }
    // 在第一次渲染后调用，只在客户端。
    // 你应该在 componentDidMount 生命周期方法内发送 AJAX 请求数据。
    // 这样你才能够在请求的数据到达时使用 setState 更新你的组件。
    componentDidMount() {

    }
    // 在组件接收到新的props或者state但还没有render时被调用。在初始化时不会被调用。
    componentWillUpdate(){

    }
    // 在组件完成更新后立即调用。在初始化时不会被调用
    componentDidUpdate(){

    }
    _handleToggle() {
        window.location.href = '/';
    }
    // 在渲染前调用,在客户端也在服务端。
    componentWillMount() {
        let that = this;
        axios.post(window.location.href, {
            params:{
                'csrf-token': document.getElementsByTagName('meta')['csrf-token'].getAttribute('content')
            }
        }).then((res)=>{
            // console.log(res.data);
            that.setState({
                pin: res.data.pin
            });
        }).catch((error)=>{
            console.log(error);
        });
    }
    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

    }
    render() {
        let layer_style = {
            clear:'both',
            overflow:'hidden',
            width:'100%',
            zIndex: 888
        };
        return (
            <div>
                <Header/>
                <div style={layer_style} className={'layerSection'}>
                    <Box color={this.state.clientWidth >767 ? 'darkWash': 'white'} display="flex" direction="row" paddingY={this.state.clientWidth >767 ? 5 : 10} position="fixed" top bottom width={'100%'} overflow={'scrollY'}>
                        <Box marginLeft={2} position={'absolute'} top marginTop={this.state.clientWidth >767 ? 4 : 0}>
                            <IconButton
                                accessibilityLabel="Close"
                                icon="arrow-back"
                                onClick={this.handleToggle}
                            />
                        </Box>
                        <Column span={ this.state.clientWidth >767 ? 2 : 0}/>
                        <Column span={this.state.clientWidth >767 ? 8 : 12}>
                            <Box color={'white'} display={this.state.clientWidth >767 ? 'flex' : 'block'} shape={this.state.clientWidth >767 ? 'rounded' : 'square'} padding={this.state.clientWidth >767 ? 5 :1 } width={'100%'}>
                                <Column span={this.state.clientWidth >767 ? 8 :12}>
                                    <Box shape={'rounded'} color={'orange'}>
                                        <Image
                                            alt={this.state.pin && this.state.pin.text ? this.state.pin.text : ''}
                                            // fit="cover"
                                            color = {'orange'}
                                            naturalWidth={ this.state.pin.origin == '微博' ? (this.state.pin.pic_detail.geo  ? this.state.pin.pic_detail.geo.width : 360) :
                                                (this.state.pin.pic_detail ? this.state.pin.pic_detail[0].config_width : 120)   }
                                            naturalHeight={this.state.pin.origin == '微博' ? (this.state.pin.pic_detail.geo  ?
                                                (this.state.pin.pic_detail.geo.height>1200 ? 1200 : this.state.pin.pic_detail.geo.height) : 540) :
                                                (this.state.pin.pic_detail ? this.state.pin.pic_detail[0].config_height : 120)}
                                            src={this.state.pin.pic_detail ? this.state.pin.pic_detail.large.url :this.state.pin.display_url}
                                        >
                                        </Image>
                                    </Box>
                                </Column>
                                <Column span={this.state.clientWidth >767 ? 4 :12 }>
                                    <Box color="white" paddingX={2} paddingY={2}>
                                        <Box display="flex" column={12} direction="row" paddingY={2}>
                                            <Box column={2}>
                                                <Link href={'/'+this.state.pin.domain} target={'blank'}>
                                                    <Avatar name={this.state.pin.name} src={this.state.pin.avatar} verified={this.state.pin.verified} />
                                                </Link>
                                            </Box>
                                            <Box column={10} paddingX={2} paddingY={1}>
                                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.pin.description}</Text>
                                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                                    <Link href={"/"+this.state.pin.domain} target={'blank'}>{this.state.pin.name}</Link>
                                                </Text>
                                            </Box>
                                        </Box>
                                        <Box display={'block'} column={12} paddingX={2} paddingY={2}>
                                            <Text color={'darkGray'}  size="xs">{this.state.pin && this.state.pin.text ? this.state.pin.text : ''}</Text>
                                        </Box>
                                        <Box paddingY={1} shape={'rounded'} color={'white'} marginTop={3} marginBottom={3} display={ 'block'}>
                                            <Link href={this.state.pin.origin_url} target={'blank'}>
                                                <Box alignItems="center" display="flex" color={'darkWash'} shape={'rounded'} paddingX={4} paddingY={2}>
                                                    <Box marginRight={1} padding={1}>
                                                        <Icon icon="arrow-up-right" accessibilityLabel="link" color="darkGray" inline={true}/>
                                                    </Box>
                                                    <Text align="center" bold color="darkGray">
                                                        weibo.com
                                                    </Text>
                                                </Box>
                                            </Link>
                                        </Box>
                                    </Box>
                                </Column>
                            </Box>
                        </Column>
                        <Column span={ this.state.clientWidth >767 ? 2 : 0}/>
                    </Box>
                </div>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('app'));
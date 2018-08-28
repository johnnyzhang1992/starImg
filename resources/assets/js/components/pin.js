import {Component} from "react";
import React from "react";
import { Image } from 'gestalt';
import { Card } from 'gestalt';
import { Box } from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
import { Icon } from 'gestalt';
import { Text } from 'gestalt';
import { Layer } from 'gestalt';
import { Column } from 'gestalt';
import { IconButton } from 'gestalt';

// box color list
const boxColor = ["blue","darkGray","darkWash","eggplant","gray","green","lightGray","lightWash","maroon","midnight","navy","olive","orange","orchid","pine","purple","red","transparent","watermelon","white"];
const imageColor = ['#7a4579','#d56073','#ec9e69',"#bad7df",'#ffe2e2','#f6f6f6','#99ddcc','#dd0a35','#e4d1d3','#1687a7','#014955','#feb062','#575151','#3f3b3b'];

class Pin extends Component {
    constructor(props) {
        super(props);
        this.handleToggle = this._handleToggle.bind(this);
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
            hovered: false,
            showLayer: false,
            src: 'https://wx3.sinaimg.cn/orj360/4a47f46cly1fu2j0ki4pfj22kw3vckjn.jpg',
        };
    }
    _handleToggle() {
        this.setState(prevState => ({
            showLayer: !prevState.showLayer
        }));
        window.history.back();
        if(document.getElementById('body').style.overflowY == 'scroll'){
            document.getElementById('body').style.overflowY ='hidden'
        }else{
            document.getElementById('body').style.overflowY ='scroll'
        }
    }
    // 在第一次渲染后调用，只在客户端。之后组件已经生成了对应的DOM结构，可以通过this.getDOMNode()来进行访问。
    componentDidMount() {

    }

    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

    }
    render() {
        const { showLayer } = this.state;
        let layer_style = {
            clear:'both',
            overflow:'hidden',
            width:'100%',
            zIndex: 888
        };
        return (
            <div style={this.state.style} className={'pinItem'}>
                <Box paddingX={2} paddingY={2}  shape={'rounded'} color={'white'}>
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
                        <div className={'PinLayer'} onClick={()=>{
                            this.setState(prevState => ({
                                showLayer: !prevState.showLayer ,
                                src: this.state.item.pic_detail ? this.state.item.pic_detail.large.url :this.state.item.display_url
                            }));
                            document.getElementById('body').style.overflowY ='hidden';
                            let title =document.getElementsByTagName('meta')['title'].getAttribute('content');
                            let state = {title:title,url:window.location.href};
                            history.pushState(state,'','pin/'+this.state.item.id);
                        }}>
                            <Box shape={'rounded'} color={'white'}>
                                <div className={'pinImage'}>
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
                                </div>
                            </Box>
                        </div>
                        <Box display="flex" direction="row" paddingY={2} marginTop={1} color={'white'}>
                            <Box column={2}>
                                <Link href={this.state.item.domain} target={'blank'}>
                                    <Avatar name={this.state.item.name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                </Link>
                            </Box>
                            <Box column={10} paddingX={2}>
                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                    <Link href={this.state.item.domain} target={'blank'}>{this.state.item.name}</Link>
                                </Text>
                            </Box>
                        </Box>
                    </Card>
                </Box>
                <Box marginLeft={1} marginRight={1}>
                    <Box padding={1}>
                        {showLayer && (
                            <Layer>
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
                                                    <Box shape={'rounded'} color={this.state.boxColor[this.state.itemIdx]}>
                                                        <Image
                                                            alt={this.state.item.text}
                                                            // fit="cover"
                                                            color = {this.state.imageColor[this.state.itemIdx]}
                                                            naturalWidth={this.state.item.pic_detail  ? this.state.item.pic_detail.geo.width : 360   }
                                                            naturalHeight={this.state.item.pic_detail  ? this.state.item.pic_detail.geo.height : 540}
                                                            src={this.state.item.pic_detail ? this.state.item.pic_detail.large.url :this.state.item.display_url}
                                                        >
                                                        </Image>
                                                    </Box>
                                                </Column>
                                                <Column span={this.state.clientWidth >767 ? 4 :12 }>
                                                    <Box color="white" paddingX={2} paddingY={2}>
                                                        <Box display="flex" column={12} direction="row" paddingY={2}>
                                                            <Box column={2}>
                                                                <Link href={'/'+this.state.item.domain} target={'blank'}>
                                                                    <Avatar name={this.state.item.name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                                                </Link>
                                                            </Box>
                                                            <Box column={10} paddingX={2} paddingY={1}>
                                                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                                                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                                                    <Link href={'/'+this.state.item.domain} target={'blank'}>{this.state.item.name}</Link>
                                                                </Text>
                                                            </Box>
                                                        </Box>
                                                        <Box display={'block'} column={12} paddingX={2} paddingY={2}>
                                                            <Text color={'darkGray'}  size="xs">{this.state.item.text}</Text>
                                                        </Box>
                                                        <Box paddingY={1} shape={'rounded'} color={'white'} marginTop={3} marginBottom={3} display={ 'block'}>
                                                            <Link href={this.state.item.origin_url} target={'blank'}>
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
                            </Layer>
                        )}
                    </Box>
                </Box>
            </div>
        );
    }
}

export default Pin;
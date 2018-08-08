import {Component} from "react";
import React from "react";
import { Image } from 'gestalt';
import { Card } from 'gestalt';
import { Box } from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
import { Icon } from 'gestalt';
import { Text } from 'gestalt';

// box color list
const boxColor = ["blue","darkGray","darkWash","eggplant","gray","green","lightGray","lightWash","maroon","midnight","navy","olive","orange","orchid","pine","purple","red","transparent","watermelon","white"];
const imageColor = ['#7a4579','#d56073','#ec9e69',"#bad7df",'#ffe2e2','#f6f6f6','#99ddcc','#dd0a35','#e4d1d3','#1687a7','#014955','#feb062','#575151','#3f3b3b'];

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
                <Box paddingX={2} paddingY={2}  shape={'rounded'}>
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
                                <Link href={this.state.item.star_id} target={'blank'}>
                                    <Avatar name={this.state.item.screen_name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                </Link>
                            </Box>
                            <Box column={10} paddingX={2}>
                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                    <Link href={this.state.item.star_id} target={'blank'}>{this.state.item.screen_name}</Link>
                                </Text>
                            </Box>
                        </Box>
                    </Card>
                </Box>
            </div>
        );
    }
}

export default Pin;
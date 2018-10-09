import {Component} from "react";
import React from "react";
import { Card } from 'gestalt';
import { Box } from 'gestalt';
import { Avatar } from 'gestalt';
import { Link } from 'gestalt';
import { Text } from 'gestalt';
import FontAwesomeIcon from "react-fontawesome";

class Star extends Component {
    constructor(props) {
        super(props);
        this.state = {
            item: props.data,
            itemIdx: props.itemIdx >10 ? parseInt(props.itemIdx % 10) : props.itemIdx,
            clientWidth: document.documentElement.clientWidth,
        };
    }
    // 在第一次渲染后调用，只在客户端。之后组件已经生成了对应的DOM结构，可以通过this.getDOMNode()来进行访问。
    componentDidMount() {

    }
    componentDidUpdate(){

    }
    // 在组件从 DOM 中移除的时候立刻被调用。
    componentWillUnmount() {

    }
    render() {
        return (
            <div className={'starItem'}>
                <Box paddingX={2} paddingY={2}  shape={'rounded'} color={'white'}>
                    <Card
                        paddingX={3}
                        paddingY={2}
                        >
                        <Box display="flex" direction="row" paddingY={2} marginTop={1} color={'white'} >
                            <Box column={2}>
                                <Link href={this.state.item.domain} target={'blank'}>
                                    <Avatar name={this.state.item.name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                </Link>
                            </Box>
                            <Box column={10} paddingX={2}>
                                <Text color={'gray'} align={'left'} truncate size="xs" >
                                    <Link href={this.state.item.domain} target={'blank'}>{this.state.item.name}</Link>
                                </Text>

                                <Box color="white" paddingY={2} alignSelf={'center'}>
                                    <Box width={24} display={(this.state.item.wb_domain || this.state.item.wb_id) ? 'inlineBlock' : 'none'}>
                                        <Link href={'https://weibo.com/'+(this.state.item.wb_domain ? this.state.item.wb_domain : 'u/'+this.state.item.wb_id)} target={'blank'}>
                                            <FontAwesomeIcon
                                                className={'f-brand'}
                                                name={'weibo'}
                                                size={'2x'}
                                            />
                                            {/*<Avatar name={'Weibo'} />*/}
                                        </Link>
                                    </Box>
                                    <Box width={24} display={this.state.item.ins_name? 'inlineBlock' : 'none'} marginLeft={2}>
                                        <Link href={'https://instagram.com/'+(this.state.item.ins_name )} target={'blank'}>
                                            {/*<Avatar name={'Instagram'} />*/}
                                            <FontAwesomeIcon
                                                className={'f-brand'}
                                                name={'instagram'}
                                                size={'2x'}
                                            />
                                        </Link>
                                    </Box>
                                    <Box width={24} display={this.state.item.fb_domain? 'inlineBlock' : 'none'} marginLeft={2}>
                                        <Link href={'https://facebook.com/'+(this.state.item.fb_domain )} target={'blank'}>
                                            {/*<Avatar name={'Instagram'} />*/}
                                            <FontAwesomeIcon
                                                className={'f-brand'}
                                                name={'facebook'}
                                                size={'2x'}
                                            />
                                        </Link>
                                    </Box>
                                </Box>

                                <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                            </Box>
                        </Box>
                    </Card>
                </Box>
            </div>
        );
    }
}

export default Star;
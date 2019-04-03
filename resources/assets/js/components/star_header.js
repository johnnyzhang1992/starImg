/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-03 22:55
 */
import React,{Component} from 'react';
import {Avatar, Box, Column, Link, Text} from "gestalt";
import FontAwesomeIcon from "react-fontawesome";


class StarHeader extends Component{
    
    shouldComponentUpdate(nextProps, nextState, nextContext) {
        return !this.props.name || nextProps.name !== this.props.name ;
    }

    render (){
        return (
            <Box display="flex" direction="row" paddingX={8} paddingY={2}>
                <Column span={this.props.clientWidth >768 ? 5 : 3} >
                    <Box color="white" paddingX={5} paddingY={3} display={'flex'} direction={'column'} alignSelf={'end'} alignItems={'end'}>
                        <Box color="white" paddingY={2} width={this.props.clientWidth >768 ? 106 : 50} alignContent={'end'} alignSelf={'end'} alignItems={'end'} display={'flex'}>
                            <Avatar name={'User name'} src={this.props.avatar } verified={this.props.verified}/>
                        </Box>
                    </Box>
                </Column>
                <Column span={this.props.clientWidth >768 ? 6: 9}>
                    <Box color="white" paddingX={5} paddingY={3}>
                        <Box color="white" paddingY={2}>
                            <Text align={'left'}>{this.props.name}</Text>
                        </Box>
                        <Box color="white">
                            <Text align={'left'} size={'xs'} color={'gray'}>{this.props.verified ? this.props.verified_reason : ''}</Text>
                        </Box>
                        <Box color="white" paddingY={1}>
                            <Text align={'left'} inline={true} bold={true}>{this.props.posts_count}</Text>
                            <Text align={'left'} inline={true}> posts</Text>
                        </Box>
                        <Box color="white" paddingY={1} display={this.props.baike && this.props.baike !='' ? 'block' : 'none'}>
                            <Text align={'left'} inline={true} color={'gray'}>百度人物资料 </Text>
                            <Text align="left" inline={true}>{this.props.description} </Text>
                            <Text align={'left'} inline={true} color={'orange'}>
                                <Link inline={true} href={this.props.baike} target={'blank'}>详情</Link>
                            </Text>
                        </Box>
                        <Box color="white" paddingY={2} display={this.props.baike && this.props.baike !='' ? 'none' : 'block'}>
                            <Text align="left" >{this.props.description}</Text>
                        </Box>
                        <Box color="white" paddingY={2} alignSelf={'center'}>
                            <Box width={24} display={(this.props.wb_domain || this.props.wb_id) ? 'inlineBlock' : 'none'}>
                                <Link href={'https://weibo.com/'+(this.props.wb_domain ? this.props.wb_domain : 'u/'+this.props.wb_id)} target={'blank'}>
                                    <FontAwesomeIcon
                                        className={'f-brand'}
                                        name={'weibo'}
                                        size={'2x'}
                                    />
                                    {/*<Avatar name={'Weibo'} />*/}
                                </Link>
                            </Box>
                            <Box width={24} display={this.props.ins_name? 'inlineBlock' : 'none'} marginLeft={2}>
                                <Link href={'https://instagram.com/'+(this.props.ins_name )} target={'blank'}>
                                    {/*<Avatar name={'Instagram'} />*/}
                                    <FontAwesomeIcon
                                        className={'f-brand'}
                                        name={'instagram'}
                                        size={'2x'}
                                    />
                                </Link>
                            </Box>
                            <Box width={24} display={this.props.fb_domain? 'inlineBlock' : 'none'} marginLeft={2}>
                                <Link href={'https://facebook.com/'+(this.props.fb_domain )} target={'blank'}>
                                    {/*<Avatar name={'Instagram'} />*/}
                                    <FontAwesomeIcon
                                        className={'f-brand'}
                                        name={'facebook'}
                                        size={'2x'}
                                    />
                                </Link>
                            </Box>
                        </Box>
                    </Box>
                </Column>
            </Box>
        )
    }
}

export default StarHeader;
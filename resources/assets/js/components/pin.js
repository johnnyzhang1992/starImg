import React,{Component} from "react";
import { Image,Card,Box,Avatar,Link,Icon, Text} from 'gestalt';
// import { Layer } from 'gestalt';
// import { Column } from 'gestalt';
// import { IconButton } from 'gestalt';
import ImageViewerBox from './image_viewer';

const imageColor = ['#7a4579','#d56073','#ec9e69',"#bad7df",'#ffe2e2','#f6f6f6','#99ddcc','#dd0a35','#e4d1d3','#1687a7','#014955','#feb062','#575151','#3f3b3b'];
class Pin extends Component {
    constructor(props) {
        super(props);
        this.state = {
            item: props.data,
            itemIdx: props.itemIdx >10 ? parseInt(props.itemIdx % 10) : props.itemIdx,
            clientWidth: document.documentElement.clientWidth,
            hovered: false,
            src: 'https://wx3.sinaimg.cn/orj360/4a47f46cly1fu2j0ki4pfj22kw3vckjn.jpg',
            page_type: document.getElementsByTagName('meta')['page-type'].getAttribute('content'),
            show_image: false,
            img_props: {}
        };
        this.handleMouseEnter = this.handleMouseEnter.bind(this);
        this.handleMouseLeave = this.handleMouseLeave.bind(this);
        this.showImageViewer = this.showImageViewer.bind(this);
        this.closeImageViewer = this.closeImageViewer.bind(this);
    }
    showImageViewer(){
        console.log('---click--');
        let width = this.state.item.origin === '微博' ? (this.state.item.pic_detail.geo  ? this.state.item.pic_detail.geo.width : 360) :
                    (this.state.item.pic_detail ? this.state.item.pic_detail[0].config_width : 120);
        let height =this.state.item.origin === '微博' ? (this.state.item.pic_detail.geo  ?
            (this.state.item.pic_detail.geo.height>1200 ? 1200 : this.state.item.pic_detail.geo.height) : 540) :
            (this.state.item.pic_detail ? this.state.item.pic_detail[0].config_height : 120);
        let winWidth = 0;
        let windowHeight = 0;
        if(document.compatMode == "CSS1Compat"){
            windowHeight = document.documentElement.clientHeight;
        }else{
            windowHeight = document.body.clientHeight;
        }
        if (window.innerWidth){
            winWidth = window.innerWidth;
        } else if((document.body) && (document.body.clientWidth)){
            winWidth = document.body.clientWidth;
        }
        width = height>windowHeight ? (windowHeight-20)/height*width : width;
        this.setState({
            show_image: true,
            img_props: {
                width: width,
                height: height>windowHeight ? windowHeight-20 : height,
                left: ((winWidth-width)/2).toFixed(2),
                img_url: this.state.item.origin === '微博' ? (this.state.item.pic_detail ? this.state.item.pic_detail.large.url : this.state.item.display_url)
                    :(this.state.item.cos_url ?  'https://star-1256165736.picgz.myqcloud.com/'+this.state.item.cos_url : this.state.item.display_url)
            }
        })
    };
    closeImageViewer(){
        this.setState({show_image: false})
    };

    // 在第一次渲染后调用，只在客户端。之后组件已经生成了对应的DOM结构，可以通过this.getDOMNode()来进行访问。
    componentDidMount() {

    }

    handleMouseEnter(){
        this.setState({
            hovered: this.state.clientWidth > 768
        })
    }
    handleMouseLeave(){
        this.setState({ hovered: false });
    }
    shouldComponentUpdate(nextProps, nextState, nextContext) {
        return nextProps.id !== this.state.item.id;
    }

    render() {
        const style = {
            clear:'both',
            overflow:'hidden',
            width:'100%',
        };
        return (
            <div style={style} className={'pinItem'} key={this.state.item.id}>
                <Box paddingX={2} paddingY={2}  shape={'rounded'} color={'white'}>
                    <Card
                        paddingX={3}
                        paddingY={2}
                        onMouseEnter={this.handleMouseEnter}
                        onMouseLeave={this.handleMouseLeave}
                        >
                        <Box shape={'rounded'} color={'white'}>
                            <div className={'pinImage'} onClick={this.showImageViewer}>
                                <Image
                                    alt={this.state.item.text}
                                    // fit="cover"
                                    color = {imageColor[this.state.itemIdx]}
                                    naturalWidth={ this.state.item.origin == '微博' ? (this.state.item.pic_detail.geo  ? this.state.item.pic_detail.geo.width : 360) :
                                        (this.state.item.pic_detail ? this.state.item.pic_detail[0].config_width : 120)   }
                                    naturalHeight={this.state.item.origin == '微博' ? (this.state.item.pic_detail.geo  ?
                                        (this.state.item.pic_detail.geo.height>1200 ? 1200 : this.state.item.pic_detail.geo.height) : 540) :
                                        (this.state.item.pic_detail ? this.state.item.pic_detail[0].config_height : 120)}
                                    src={this.state.item.origin == '微博' ? (this.state.item.pic_detail ?
                                        this.state.item.pic_detail.url :this.state.item.display_url) :
                                        ('https://star-1256165736.picgz.myqcloud.com/'+this.state.item.cos_url+'!small')}
                                >
                                    <Box paddingX={3} paddingY={1} position={'absolute'} bottom={true} left={true} shape={'rounded'} color={'white'} marginLeft={3} marginBottom={3} display={this.state.hovered ? 'block' : 'none'}>
                                        <Link href={this.state.item.origin == '微博' ? this.state.item.origin_url : 'https://instagram.com/p/'+this.state.item.code}>
                                            <Box alignItems="center" display="flex">
                                                <Box marginRight={1} padding={1}>
                                                    <Icon icon="arrow-up-right" accessibilityLabel="link" color="darkGray" inline={true}/>
                                                </Box>
                                                <Text align="center" bold color="darkGray">
                                                    {this.state.item.origin == '微博' ? 'weibo.com' : 'instagram.com'}
                                                </Text>
                                            </Box>
                                        </Link>
                                    </Box>
                                </Image>
                            </div>
                        </Box>
                        {
                            this.state.page_type && this.state.page_type =='normal' ?
                                <Box display="flex" direction="row" paddingY={2} marginTop={1} color={'white'} >
                                    <Box column={2}>
                                        <Link href={this.state.item.domain} target={'blank'}>
                                            <Avatar name={this.state.item.name} src={this.state.item.avatar} verified={this.state.item.verified} />
                                        </Link>
                                    </Box>
                                    <Box column={10} paddingX={2}>
                                        <Link href={'https://starimg.cn/pin/'+this.state.item.id} target={'blank'} className={'PinLayer'}>
                                            <Text color={'darkGray'} align={'left'} truncate size="xs">{this.state.item.description}</Text>
                                        </Link>
                                        <Text color={'gray'} align={'left'} truncate size="xs" >
                                            <Link href={this.state.item.domain} target={'blank'}>{this.state.item.name}</Link>
                                        </Text>
                                    </Box>
                                </Box>
                                : ''
                        }

                    </Card>
                </Box>
                {this.state.show_image && (
                    <ImageViewerBox
                        onClose={this.closeImageViewer}
                        img_url={this.state.img_props.img_url}
                        left={this.state.img_props.left}
                        width={this.state.img_props.width}
                        height={this.state.img_props.height}
                    />
                )}
            </div>
        );
    }
}

export default Pin;
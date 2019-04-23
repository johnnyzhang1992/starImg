/**
 * Created by PhpStorm.
 * Author: johnnyzhang
 * Date: 2019-04-23 23:09
 */
import React,{Component} from 'react';
import ReactDom  from 'react-dom';
class ImageViewerBox extends Component{
    constructor(props) {
        super(props);
        this.state = {
            img_url: props.img_url ? props.img_url : 'https://starimg.cn/loading.gif'
        };
        // 网页根节点下创建一个 div 节点
        this.container = document.createElement('div');
        document.body.appendChild(this.container);
    }
    componentDidMount() {

    }

    componentWillUnmount() {
        document.body.removeChild(this.container);
    }

    render() {
        const style= {
            top: '10px',
            left: this.props.left+'px',
            width: this.props.width+'px',
            height: this.props.height+'px'
        };
        return ReactDom.createPortal(
            <div className='image-viewer-box'>
               <div className='image-viewer' onClick={this.props.onClose}>
                   <div className='image-box'>
                       <img src={this.state.img_url} alt="" className="img" style={style}/>
                   </div>
               </div>
            </div>,
            this.container)
    }
}

export default  ImageViewerBox;
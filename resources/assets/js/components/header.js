import {Component} from "react";
import React from "react";
import { Text } from 'gestalt';
import { Box } from 'gestalt';
import { Button } from 'gestalt';
import { Link } from 'gestalt';
import { Sticky } from 'gestalt';
import { Divider } from 'gestalt';
import { SearchField } from 'gestalt';
// import { IconButton } from 'gestalt';

class Header extends Component {
    constructor(props) {
        super(props);
        this.state = { value: '' };
    }
    render(){
        return (
                <div className={'header'}>
                    <Sticky top={0} dangerouslySetZIndex={{ __zIndex: 671 }}>
                        <Box color="white" shape="rounded" paddingX={8} paddingY={3} display="flex" direction="row" alignItems="center">
                            <Box padding={2}>
                                <Link href="/">
                                    <Text bold>starImg</Text>
                                </Link>
                            </Box>
                            <Box flex="grow" paddingX={2}>
                                <div className={'searchBox'}>
                                    <SearchField
                                        accessibilityLabel="Demo Search Field"
                                        id="searchField"
                                        onChange={({ value }) => this.setState({ value })}
                                        placeholder="Search and explore,now no word"
                                        value={this.state.value}
                                    />
                                </div>
                            </Box>
                            <Box paddingX={2} shape={'pill'} marginLeft={-2} marginRight={-2}>
                                <Link href={'/'}>
                                    <Button color="white" text={'首页'}/>
                                </Link>
                            </Box>
                            <Box paddingX={2} shape={'pill'} marginLeft={-2} marginRight={-2}>
                                <Link href={'/explore'}>
                                    <Button color="white" text={'发现'}/>
                                </Link>
                            </Box>
                            {/*<Box paddingX={2}>*/}
                                {/*<IconButton accessibilityLabel="Profile" icon="person" size="md" />*/}
                            {/*</Box>*/}
                        </Box>
                        <Divider />
                    </Sticky>
                </div>
            )
    }
}

export default Header
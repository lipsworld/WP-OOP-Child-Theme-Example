import React from 'react';
import style from './Container.css';
import Helmet from 'react-helmet';
import { Layout, Menu, Spin } from 'antd';
import PropTypes from 'prop-types';
import { Link } from 'dva/router';

const { Header, Content, Footer } = Layout;

class Container extends React.Component {
  
  render() {
    const styles = {...{ background: '#fff', padding: 24, minHeight: 280 }, ...this.props.styles}
    
    return (
      <Layout className={style.layout}>
        <Helmet>
          <title>{this.props.title}</title>
        </Helmet>
        <Header>
          <div className={style.logo}><h1><Link to="/">Weather</Link></h1></div>
          <Menu
            theme="dark"
            mode="horizontal"
            style={{ lineHeight: '64px' }}
          >
          </Menu>
        </Header>

        <Content style={{ padding: '0 50px' }}>
          <Spin size="large" spinning={this.props.loading}>
            <div style={styles}>
              {this.props.children}
            </div>
          </Spin>
        </Content>

        <Footer className={style.footer}>
          <p>Created by <a target="_blank" rel="noopener noreferrer" href="https://sisir.me">Sisir K. Adhikari</a></p>
        </Footer>

      </Layout>
    )
  }
}

Container.propTypes = {
  styles: PropTypes.object,
  loading: PropTypes.bool
};

Container.defaultProps = {
  styles: {},
  loading: false
}

export default Container;

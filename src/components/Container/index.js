import React from 'react';
import style from './Container.css';
import Helmet from 'react-helmet';
import { Layout, Input, Spin } from 'antd';
import PropTypes from 'prop-types';
import { Link } from 'dva/router';

const { Header, Content, Footer } = Layout;
const { Search } = Input;


class Container extends React.Component {
  
  onSearch = (value) => {
    value = value.trim();
    
    if( value === ''){
      return;
    }

    this.props.onSearch(value);
  }

  render() {
    const styles = {...{ background: '#fff', padding: 24, minHeight: 280 }, ...this.props.styles}
    
    return (
      <Layout className={style.layout}>
        <Helmet>
          <title>{this.props.title}</title>
        </Helmet>
        <Header className={style.header}>
          <div className={style.logo}><h1><Link to="/">Weather</Link></h1></div>
          <div className={style.right}>
            <Search
              required
              className={style.search}
              placeholder="Search city"
              onSearch={this.onSearch}
              style={{ width: 200 }}
              size="large"
            />
          </div>
        </Header>

        <Content style={{ padding: '0 50px' }}>
          <Spin size="large" spinning={this.props.loading}>
            <div style={styles}>
              {this.props.children}
            </div>
          </Spin>
        </Content>

        <Footer className={style.footer}>
          <p>Created by <a target="_blank" rel="noopener noreferrer" href="https://sisir.me">Sisir K. Adhikari</a>. Powered by <a target="_blank" rel="noopener noreferrer" href="https://www.metaweather.com/">MetaWeather.com</a></p>
        </Footer>

      </Layout>
    )
  }
}

Container.propTypes = {
  styles: PropTypes.object,
  loading: PropTypes.bool,
  onSearch: PropTypes.func
};

Container.defaultProps = {
  styles: {},
  loading: false
}

export default Container;

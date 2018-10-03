import React from 'react';
import { connect } from 'dva';
import Container from '../components/Container';
import PropTypes from 'prop-types';
import styles from './IndexPage.css';

class IndexPage extends React.Component {
  render(){
    return (
      <Container loading={this.props.loading}>
        
      </Container>
    );
  }
}

IndexPage.propTypes = {
  loading: PropTypes.bool
};

const mapStateToProps = (state) => {
  return {
    loading: state.loading.global,
    //ui: state.ui
  }
}

export default connect(mapStateToProps)(IndexPage);

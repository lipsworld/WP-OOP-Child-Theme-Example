import React from 'react';
import { connect } from 'dva';
import Container from '../components/Container';
import PropTypes from 'prop-types';
import Weather from '../components/Weather';
import { Row, Col } from 'antd';

class IndexPage extends React.Component {

  componentDidMount(){
    // default cities & woeid
    const cities = ['Dhaka', 'Kolkata', 'Delhi', 'Bangkok', 'Singapore', 'London'];
    this.props.dispatch({type: 'locations/fetch', payload: cities});
  }

  onSearch = (value) => {
    this.props.history.push('/search/' + encodeURIComponent(value));
  }

  loadDetails = (woeid) => {
    this.props.dispatch({type: 'locations/fetchSingle', payload: {woeid: woeid}})
  }

  render(){
    const colStyle = {marginBottom: '20px'};
    const width = {
      xl: 8,
      lg: 12,
      md: 12,
      xs: 24
    }
    
    return (
      <Container onSearch={this.onSearch} loading={this.props.loading} styles={{backgroundColor: 'transparent'}}>
        <Row gutter={16}>
          {this.props.locations && this.props.locations.map(city => 
            <Col {...width} style={colStyle} key={city.woeid}>
              <Weather history={this.props.history} loading={true} woeid={city.woeid} data={city} loadDetails={this.loadDetails}></Weather>
            </Col>            
          )}
        </Row>
        
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
    locations: state.locations.locations
    //ui: state.ui
  }
}

export default connect(mapStateToProps)(IndexPage);

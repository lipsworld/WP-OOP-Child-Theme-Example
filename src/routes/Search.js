import React from 'react';
import { connect } from 'dva';
import Container from '../components/Container';
import PropTypes from 'prop-types';
import Weather from '../components/Weather';
import { Row, Col, Alert } from 'antd';

class Search extends React.Component {

  componentDidMount(){

    if(this.props.match.params.query.trim() === ''){
      this.props.history.push('/');
    }

    if(this.props.searchResult.length === 0){
      this.props.dispatch({type: 'locations/search', payload: this.props.match.params.query});
    }
  }

  onSearch = (value) => {

    value = value.trim();

    if(value === ''){
      return false;
    }
    
    // values are same, no need to search
    if(this.props.match.params.query === value){
      return;
    }
    
    value = encodeURIComponent(value);
    this.props.history.push('/search/' + value);
    this.props.dispatch({type: 'locations/search', payload: value});
  }

  loadDetails = (woeid) => {
    this.props.dispatch({type: 'locations/fetchSingle', payload: {woeid: woeid, search: true}})
  }

  render(){
    const colStyle = {marginBottom: '20px'};
    const width = {
      xl: 8,
      lg: 12,
      md: 12,
      xs: 24
    }

    let containerStyle = {};

    if(this.props.searchResult.length > 0){
      containerStyle = {backgroundColor: 'transparent'};
    }

    let notFoundMessage = "No location is found matching your search query. Please try again!";
    if(this.props.loading){
      notFoundMessage = 'Wait...';
    }

    return (
      <Container onSearch={this.onSearch} loading={this.props.loading} styles={containerStyle}>
        <Row gutter={16}>
          {!this.props.searchResult.length &&
            <Alert style={{textAlign: 'center'}} message={notFoundMessage} type="info" />
          }
          {this.props.searchResult && this.props.searchResult.map(city => 
            <Col {...width} style={colStyle} key={city.woeid}>
              <Weather loading={true} woeid={city.woeid} data={city} loadDetails={this.loadDetails}></Weather>
            </Col>            
          )}
        </Row>
        
      </Container>
    );
  }
}

Search.propTypes = {
  loading: PropTypes.bool
};

const mapStateToProps = (state) => {
  return {
    loading: state.loading.global,
    searchResult: state.locations.searchResult
  }
}

export default connect(mapStateToProps)(Search);

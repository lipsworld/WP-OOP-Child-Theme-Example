import React from 'react';
import { connect } from 'dva';
import Container from '../components/Container';
import PropTypes from 'prop-types';
import { Row, Col, Button, Divider } from 'antd';
import WeatherDetails from '../components/WeatherDetails';

class Location extends React.Component {

  componentDidMount(){
    
    let isSearch = false;
    if(typeof this.props.location.state !== 'undefined'){
      if(typeof this.props.location.state.isSearch === 'boolean'){
        isSearch = this.props.location.state.isSearch;
      }
    };

    this.props.dispatch({
      type: 'locations/single', 
      payload: {
        woeid: this.props.match.params.id, 
        isSearch: isSearch
      }
    });
  }

  onSearch = (value) => {
    this.props.history.push('/search/' + encodeURIComponent(value));
  }

  onBackButtonClick = () => {
    this.props.history.goBack();
  }

  render(){
    const colStyle = {marginBottom: '20px'};
    const style = {
      backgroundColor: 'white',
      padding: '16px'
    }

    return (
      <Container onSearch={this.onSearch} loading={this.props.loading}  styles={{backgroundColor: 'transparent'}}>

        {this.props.data &&
          <div>
            <Row gutter={16}>
              <Col sm={2}>
                <Button onClick={this.onBackButtonClick} block size="large" icon="left" type="primary">Back</Button>
              </Col>
              <Col sm={22}>
                <div style={style}>
                  <strong>Location: </strong> {this.props.data.title}
                </div>
              </Col>
            </Row>
          
            <Row gutter={16}>
                <Col>
                
                  <Divider />

                  {this.props.data.consolidated_weather.map( (weather, i) => {
                      return (
                          <Col style={colStyle} key={i}>
                            <WeatherDetails data={weather} />
                          </Col>  
                        );
                    }
                  )}

                </Col>
            </Row>

          </div>        
          
        }
        
      </Container>
    );
  }
}

Location.propTypes = {
  loading: PropTypes.bool
};

const mapStateToProps = (state) => {
  return {
    loading: state.loading.global,
    data: state.locations.current
  }
}

export default connect(mapStateToProps)(Location);

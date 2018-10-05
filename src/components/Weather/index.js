import React from 'react';
import PropTypes from 'prop-types';
import styles from './Weather.css';
import { Card, Icon, Col, Row } from "antd";
import { round } from '../../utils';

class Weather extends React.Component {
    
    componentDidMount(){
        if(! ('consolidated_weather' in this.props.data) ){
            this.props.loadDetails(this.props.woeid);
        }
    }

    showDetails = () => {
        const route = '/weather/' + this.props.woeid;
        this.props.history.push({
            pathname: route,
            state: {
                isSearch: this.props.isSearch
            }
        })
    }

    render(){

        const loading = ! ('consolidated_weather' in this.props.data );
        
        let weather_state_abbr, weather_state_name, the_temp, max_temp, min_temp, humidity;

        if(!loading){
            weather_state_abbr = this.props.data.consolidated_weather[0].weather_state_abbr;
            weather_state_name = this.props.data.consolidated_weather[0].weather_state_name;
            the_temp = this.props.data.consolidated_weather[0].the_temp;
            max_temp = this.props.data.consolidated_weather[0].max_temp;
            min_temp = this.props.data.consolidated_weather[0].min_temp;
            humidity = this.props.data.consolidated_weather[0].humidity;
        }

        the_temp = round(the_temp);
        max_temp = round(max_temp);
        min_temp = round(min_temp);

        let humidityText = '---';
        if(!loading){
            humidityText = 'Humidity: ' + humidity + '%';
        }
        
        const iconUrl = "https://www.metaweather.com/static/img/weather/" + weather_state_abbr + ".svg";
        let routeLink = <a onClick={this.showDetails} className={styles.more}>More <Icon type="arrow-right" /></a>
        
        if(loading){
            routeLink = <a>Loading...</a>;
        }
        return (
            <Card
            loading={loading}
            className="weather-card"
            title={<span className={styles.cardTitle}>{this.props.data.title}</span>}
            actions={[<span>{humidityText}</span>, routeLink]}
            >
                <Row gutter={8}>
                    <Col span={12}>
                        <Col md={8} sm={24}>
                            <img className={styles.imgResponsive} alt={weather_state_abbr} width={60} src={iconUrl} />
                        </Col>
                        <Col md={16} sm={24}>
                            <h2>{the_temp} ℃</h2>
                            <p>{weather_state_name}</p>
                        </Col>
                    </Col>
                    <Col span={12}>
                        <div className={styles.tempRange}><Icon type="caret-up"/> Max: {max_temp} ℃</div>
                        <div className={styles.tempRange}><Icon type="caret-down"/> Min: {min_temp} ℃</div>
                    </Col>
                </Row>
            </Card>
        );
    }
}

Weather.propTypes = {
    styles: PropTypes.object,
    loading: PropTypes.bool,
    data: PropTypes.object.isRequired,
    woeid: PropTypes.number.isRequired,
    history: PropTypes.object.isRequired
};

Weather.defaultProps = {
    styles: {},
    loading: false,
    isSearch: false
}

export default Weather;
  
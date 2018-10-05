import React from 'react';
import PropTypes from 'prop-types';
import styles from './WeatherDetails.css';
import { Card, Icon, Col, Row } from "antd";
import { round } from '../../utils';
import Moment from 'react-moment';
import { WiThermometer, WiHumidity, WiBarometer } from 'weather-icons-react';
import Compass from '../Compass';

class WeatherDetails extends React.Component {
    
    render(){

        const calendarStrings = {
            lastDay : '[Yesterday]',
            sameDay : '[Today]',
            nextDay : '[Tomorrow]',
            lastWeek : '[last] dddd',
            nextWeek : 'dddd',
            sameElse : 'L'
        };
      
        const iconColor = "#BEBDBD";

        let { 
            weather_state_abbr, air_pressure, weather_state_name, 
            the_temp, max_temp, min_temp, humidity, applicable_date, 
            wind_speed, wind_direction_compass 
        } = this.props.data;
        
        the_temp = round(the_temp);
        max_temp = round(max_temp);
        min_temp = round(min_temp);
        air_pressure = round(air_pressure);

        const iconUrl = "https://www.metaweather.com/static/img/weather/" + weather_state_abbr + ".svg";
        
        let title =  <Moment calendar={calendarStrings}>{applicable_date}</Moment>

        const cols = {
            md: 8,
            lg: 4,
            sm: 12,
            xs: 24
        }
        return (
            <Card
            className="weather-card"
            title={<span className={styles.cardTitle}>{title}</span>}
            >
                <Row gutter={20} type="flex" justify="space-around" align="middle">
                    <Col {...cols}>
                        <img className={styles.imgResponsive} width={300} src={iconUrl} alt={weather_state_abbr} />
                    </Col>
                    <Col {...cols} className={styles.textCenter}>
                        <h2>{the_temp} ℃</h2>
                        <p>{weather_state_name}</p>
                        <small><Moment format="dddd, MMMM Do">{applicable_date}</Moment></small>
                    </Col>
                    <Col {...cols}>
                        <div><WiThermometer size={150} color={iconColor} /></div>
                        <div className={styles.tempRange}><Icon type="caret-up"/> Max: {max_temp} ℃</div>
                        <div className={styles.tempRange}><Icon type="caret-down"/> Min: {min_temp} ℃</div>
                    </Col>
                    <Col {...cols} className={styles.textCenter}>
                        <div><WiHumidity size={150} color={iconColor} /></div>
                        <p>Humidity</p>
                        <h3>{humidity}%</h3>
                    </Col>
                    <Col {...cols} className={styles.textCenter}>
                        <div><WiBarometer size={150} color={iconColor} /></div>
                        <p>Air Pressure</p>
                        <h3>{air_pressure} Mbar</h3>
                    </Col>
                    <Col {...cols} className={styles.textCenter}>
                        <Compass speed={wind_speed} direction={wind_direction_compass} unit="mph" />
                    </Col>
                </Row>
            </Card>
        );
    }
}

WeatherDetails.propTypes = {
    styles: PropTypes.object,
    data: PropTypes.object.isRequired
};

WeatherDetails.defaultProps = {
    styles: {},
    data: {},
}

export default WeatherDetails;

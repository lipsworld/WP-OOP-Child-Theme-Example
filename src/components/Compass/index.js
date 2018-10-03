import React from 'react';
import PropTypes from 'prop-types';
import styles from './Compass.less';
import { round } from '../../utils';

class Compass extends React.Component {
    
    render(){
        const unit = this.props.unit.toUpperCase();
        const speed = round(this.props.speed);
        const direction = this.props.direction.toLowerCase();
        const arrowClassName = styles.arrow + ' ' + styles[direction];
        return (
            <div className={styles.compass}>
                <div className={styles.direction}>
                    <p>{direction.toUpperCase()}<span>{speed} {unit}</span></p>
                </div>
                <div className={arrowClassName}></div>
            </div>
        );
    }
}

Compass.propTypes = {
    direction: PropTypes.string.isRequired,
    speed: PropTypes.number.isRequired,
    unit: PropTypes.string.isRequired
}

export default Compass;

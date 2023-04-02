import { isNumber } from 'lodash';
import moment from 'moment';

export const formatCurrency = ( amount ) => {
    return isNumber ? new Intl.NumberFormat( 'en-US', {
        style: 'currency',
        currency: 'NGN',
        maximumFractionDigits: 2,
        minimumFractionDigits: 0,
    } ).format( amount ) : 'NGN 0.00';
}

export const humanizeTime = ( time ) => {
    return time ? moment( time ).fromNow() : '--';
}

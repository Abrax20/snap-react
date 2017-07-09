import { combineReducers } from 'redux';
import * as snapReducer from './snap'

export default combineReducers(Object.assign(
  snapReducer,
));

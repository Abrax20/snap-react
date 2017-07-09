// @flow
import React, { Component, AppRegistry } from 'react';
import App from './src/App.js';

export default class reactSnap extends Component {
  render() {
    return (
      <App />
    );
  }
}

AppRegistry.registerComponent('reactSnap', () => reactSnap);

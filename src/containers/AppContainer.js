// @flow
import React, { Component } from 'react';
import { View, Text } from 'react-native';
import { Router, Scene } from 'react-native-router-flux';
import Example from './../components/Example.js';
import Camera from './../components/Camera.js';
export default class AppContainer extends Component {
  render() {
    return (
      <Router>
        <Scene key="root">
          <Scene key="Example"
            component={Example}
            initial={true}
            hideNavBar
          />
          <Scene
            key="pageTwo"
            component={Camera}
            title="Camera"
            hideNavBar
           />
        </Scene>
      </Router>
    );
  }
}

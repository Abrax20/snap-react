// @flow
import React, { Component } from 'react';
import { View, Text } from 'react-native';
import { Router, Scene } from 'react-native-router-flux';
import Example from './../components/Example.js';
import TakeASnap from './../components/TakeASnap.js';
import Upload from './../components/Upload.js';

export default class AppContainer extends Component {
  render() {
    return (
      <Router>
        <Scene key="root">
          <Scene
            key="example"
            component={Example}
            title="Upload"
            initial={true}
            hideNavBar
          />
          <Scene
            key="camera"
            component={TakeASnap}
            title="Camera"
            hideNavBar
           />
           <Scene
             key="upload"
             component={Upload}
             title="Upload"
             hideNavBar
            />
        </Scene>
      </Router>
    );
  }
}

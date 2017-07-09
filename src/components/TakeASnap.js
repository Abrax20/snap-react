// @flow
import React, { Component } from 'react';
import { View, Text, StyleSheet, TouchableHighlight } from 'react-native';
import { Icon } from 'native-base';
import Camera from 'react-native-camera';
import { Actions } from 'react-native-router-flux';

export default class TakeASnap extends Component {
  constructor(props) {
    super(props);

    this.camera = null;

    this.state = {
      camera: {
        aspect: Camera.constants.Aspect.fill,
        captureTarget: Camera.constants.CaptureTarget.cameraRoll,
        type: Camera.constants.Type.back,
        orientation: Camera.constants.Orientation.auto,
        flashMode: Camera.constants.FlashMode.auto,
      },
      isRecording: false
    };
  }

  render() {
    return (
        <View
          style={styles.container}
        >
          <Camera
            ref={(cam) => {
              this.camera = cam;
            }}
            style={styles.preview}
            type={this.state.camera.type}
            aspect={Camera.constants.Aspect.fill}
          >
            <TouchableHighlight style={styles.item} onPress={this.takePicture.bind(this)}>
              <Icon
                name="camera"
                style={{fontSize: 35, color: '#fff'}}
              />
            </TouchableHighlight>
            <TouchableHighlight style={styles.item} onPress={this.switchType.bind(this)}>
              <Icon
                name="refresh"
                style={{fontSize: 35, color: '#fff'}}
              />
            </TouchableHighlight>
          </Camera>

        </View>
    );
  }

  switchType = () => {
    let newType;
    const { back, front } = Camera.constants.Type;

    if (this.state.camera.type === back) {
      newType = front;
    } else if (this.state.camera.type === front) {
      newType = back;
    }

    this.setState({
      camera: {
        ...this.state.camera,
        type: newType,
      },
    });
  }

  takePicture() {
    const options = {};

    //options.location = ...
    this.camera.capture({metadata: options})
      .then((data) => {
        Actions.upload({path: data.path});
        console.log(data);
      })
      .catch(err => console.error(err));
  }
}
const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: 'row',
    backgroundColor: 'white',
    justifyContent: 'space-between',
  },
  preview: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'flex-end',
    flexDirection: 'row',
  },
  item: {
    height: 80,
    width: 80,
    alignItems: 'center',
    justifyContent: 'center',
    flex: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    padding: 20,
    margin: 15,
    borderRadius: 80,
  }
});

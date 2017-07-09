// @flow
import React, { Component } from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Container, Header, Content, Footer, Title, Icon } from 'native-base';
import Camera from 'react-native-camera';

export default class Camera extends Component {
  render() {
    return (
      <Container>
        <Header>
            <Title>Camera</Title>
        </Header>
        <Content>
          <Camera
            ref={(cam) => {
              this.camera = cam;
            }}
            style={styles.preview}
            aspect={Camera.constants.Aspect.fill}>
            <Text style={styles.capture} onPress={this.takePicture.bind(this)}>[CAPTURE]</Text>
          </Camera>
        </Content>
      </Container>
    );
  }
}

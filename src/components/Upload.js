// @flow
import React, { Component } from 'react';
import { View, Text, StyleSheet, TouchableHighlight } from 'react-native';
import { Container, Header, Content, Footer, Title, Icon } from 'native-base';
import FileUploader from 'react-native-file-uploader'
import { Actions } from 'react-native-router-flux';

export const settings = {
};
export default class Upload extends Component {


  render() {
    return (
      <Container>
        <Header>
            <Title>Header</Title>
        </Header>
        <Content>
          <Text>{ this.props.path } </Text>
        </Content>
        <Footer>
          <TouchableHighlight
            onPress={Actions.camera}
          >
            <Title>
              <Text>
                Close
              </Text>
            </Title>
        </TouchableHighlight>
        </Footer>
      </Container>
    );
  }
  uploadfile() {
    // const settings = {
    //   '10.0.2.15',
    //   'r',
    //   'POST'
    //   'image.jpg',
    //   this.props.path,
    //   'application/octet-stream',
    //   data: {}
    // };
    //
    // FileUploader.upload(settings, (err, res) => {
    // }, (sent, expectedToSend) => {
    // });
    Actions.example();
  }
}

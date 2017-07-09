// @flow
import React, { Component } from 'react';
import { View, Text, StyleSheet, TouchableHighlight } from 'react-native';
import { Container, Header, Content, Footer, Title, Icon } from 'native-base';
import { Actions } from 'react-native-router-flux';

export default class Example extends Component {
  render() {
    return (
      <Container>
        <Header>
            <Title>Header</Title>
        </Header>
        <Content>
          <Text>Hallo</Text>
        </Content>
        <Footer>
          <TouchableHighlight
            // onPress={() => { console.log('Click'); }}
            onPress={Actions.camera}
          >
            <Title>
              <Text
              >
                Klick mich
              </Text>
              {/* <Icon
                name="camera"
                style={{fontSize: 50}}
                onPress={Actions.camera}
              /> */}
            </Title>
        </TouchableHighlight>
        </Footer>
      </Container>
    );
  }
}

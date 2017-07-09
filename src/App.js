// @flow
import React, { Component } from 'react';
import { View, Text } from 'react-native';
import {Container, Header, Content, Footer, Title } from 'native-base';

export default class App extends Component {
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
          <Title>Footer</Title>
        </Footer>
      </Container>
    );
  }
}

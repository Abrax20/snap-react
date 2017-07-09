// @flow
import React, { Component } from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Container, Header, Content, Footer, Title, Icon } from 'native-base';

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
          <Title>
              <Icon
                name="camera"
                style={{fontSize: 50}}
              />
          </Title>
        </Footer>
      </Container>
    );
  }
}

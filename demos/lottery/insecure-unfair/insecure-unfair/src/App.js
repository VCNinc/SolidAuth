import React, { Component } from 'react';
import './App.css';
import { Redirect } from 'react-router-dom';


class App extends Component {
  
  render() {
    return (
      <>
      	<Redirect to="/game/5" />
      </>
    );
  }
    
}

export default App;

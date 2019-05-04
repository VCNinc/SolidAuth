import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import Game from './game';
import * as serviceWorker from './serviceWorker';
import { Route, Link, HashRouter } from 'react-router-dom';

const routing = (
  <HashRouter>
    <div>
    	<Route exact path="/" component={App} />
    	<Route path="/game/:digit" component={Game} />
    </div>
  </HashRouter>
)

ReactDOM.render(routing, document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();

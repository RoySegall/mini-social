import React from 'react';
import ReactDOM from 'react-dom';
import Login from './Login';
import Homepage from './HomePage';
import registerServiceWorker from './registerServiceWorker';

if (window.localStorage.getItem('uid') == null) {
    ReactDOM.render(<Login />, document.getElementById('root'));
}
else {
    ReactDOM.render(<Homepage />, document.getElementById('root'));
}
registerServiceWorker();

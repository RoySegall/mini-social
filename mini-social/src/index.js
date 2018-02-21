import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Login from './Login';
import Homepage from './HomePage';
import registerServiceWorker from './registerServiceWorker';

class AppComponent extends Component {

  componentDidMount() {
    registerServiceWorker();
  }

  render() {
    if (window.localStorage.getItem('uid') == null) {
      return <Login />;
    }
    else {
      return <Homepage uid={window.localStorage.getItem('uid')} />;
    }
  }
}

ReactDOM.render(<AppComponent />, document.getElementById('root'));

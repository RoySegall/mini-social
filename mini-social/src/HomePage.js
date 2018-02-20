import React, { Component } from 'react';
import './Login.css';

class Login extends Component {
  render() {
    return (
      <div className="Homepage">
          <button>Show all friends</button>
          <button>Show birthdays</button>
          <button>Show potentials friends</button>
          <button>Show upcoming birthdays</button>
      </div>
    );
  }
}

export default Login;

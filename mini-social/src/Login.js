import React, {Component} from 'react';
import './Login.css';
import * as axios from "axios";
import * as request from "request"
// import settings from './settings';

class Login extends Component {

  constructor(props) {
    super(props);

    this.errors = "";

    this.state = {
      username: '',
      password: '',
    }
  }

  render() {
    return (
      <div className="Login">

        {this.state.errors}

        <div className="form-element username">
          <label>Username</label>
          <input
            id="username"
            type="text"
            placeholder="Username"
            onChange={(event) => this.setState({username: event.target.value})}/>
        </div>

        <div className="form-element username">
          <label>Password</label>
          <input type="password" placeholder="Password"
                 onChange={(event) => this.setState({password: event.target.value})}/>
        </div>

        <div className="actions">
          <button className="push_button blue"
                  onClick={(event) => this.handleClick(event, this)}>Log me in Scotty
          </button>
        </div>

      </div>
    );
  }

  handleClick(event, obj) {
    // Reset the errors.
    let errors = [];

    if (this.state.username === "") {
      errors.push('The username is required');
    }

    if (this.state.password === "") {
      errors.push('The password is required');
    }

    if (errors.length == 0) {
      axios.post("http://localhost/mini-social/server/login?XDEBUG_SESSION_START=11004",
        "username=" + this.state.username + "&password=" + this.state.password)
        .catch(function(error) {
          obj.setState({errors: error.response.data.error + "."});
        })
        .then(function(response){
          console.log('saved successfully')
        });
    }

    obj.setState({errors: errors.join(", ") + "."});
  }
}

export default Login;

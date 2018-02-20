import React, {Component} from 'react';
import './Login.css';
import * as axios from "axios";
import settings from './settings';

class Login extends Component {

    constructor(props) {
        super(props);

        this.state = {
            username: '',
            password: '',
            errors: [],
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
            axios.post(settings.backend + "/login",
                "username=" + this.state.username + "&password=" + this.state.password)
                .then(function (response) {
                    window.localStorage.setItem('uid', response.data.id);
                    window.location.reload();
                })
                .catch(function (error) {
                    if (error.response !== undefined) {
                        obj.setState({errors: error.response.data.error});
                    }
                });
        }

        obj.setState({errors: errors.join(", ")});
    }
}

export default Login;

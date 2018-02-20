import React, {Component} from 'react';
import './Homepage.css';
import settings from './settings';

class Login extends Component {

    constructor() {
        super();

        this.state= {
            results: <h2>Hello there! Please do something with the buttons on the left.</h2>
        };
    }

    showAllFriends() {
        let friends = ['a', 'b', 'bar'];
        this.setState({results: <ul>{friends.map((item) => <li>{item}</li>)}</ul>});
    }

    render() {
        return (
            <div className="Homepage">
                <div className="first">
                    <button className="actions" onClick={this.showAllFriends}>Show all friends</button>
                    <button className="actions">Show birthdays</button>
                    <button className="actions">Show potentials friends</button>
                    <button className="actions">Show upcoming birthdays</button>
                </div>
                <div className="last">
                    {this.state.results}
                </div>
            </div>
        );
    }
}

export default Login;

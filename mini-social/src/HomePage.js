import React, {Component} from 'react';
import './Login.css';
import settings from 'settings';

class Login extends Component {

    constructor() {
        super();
        let friends = ['a', 'b', 'bar'];
        this.name = <ul>{friends.map((item) => <li>{item}</li>)}</ul>;
    }

    render() {
        return (
            <div className="Homepage">
                <button>Show all friends</button>
                <button>Show birthdays</button>
                <button>Show potentials friends</button>
                <button>Show upcoming birthdays</button>
                {this.name}
            </div>
        );
    }
}

export default Login;

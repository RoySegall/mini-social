import React from 'react';
import './Homepage.css';
import settings from './settings';
import * as axios from "axios";
import User from './User';

class Login extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            uid: props.uid,
            results: <h2>Hello there! Please do something with the buttons on the left.</h2>
        };
    }

    showAllFriends() {
        let friends = [];
        var self = this;

        axios.get(settings.backend + '/friends/show/all?uid=' + this.state.uid)
            .then((response) => {

                response.data.forEach((item) => {
                    friends.push(item);
                });

                self.setState({
                    results: <ul>{friends.map((item) => <li><User obj={item} currentUid={this.state.uid}/></li>)}</ul>
                });
            })
            .catch((error) => {

            });
    }

    showBirthdays() {
        let friends = ['Noy'];
        this.setState({
            results: <ul>{friends.map((item) => <li>{item}</li>)}</ul>
        });
    }

    showPotentialFriends() {
        let friends = ['Donald'];
        this.setState({
            results: <ul>{friends.map((item) => <li>{item}</li>)}</ul>
        });
    }

    showUpcomingBirthdays() {
        let friends = ['Rick'];
        this.setState({
            results: <ul>{friends.map((item) => <li>{item}</li>)}</ul>
        });
    }

    render() {
        return (
            <div className="Homepage">
                <div className="first">
                    {/*<button className="actions" onClick={this.showAllFriends(this)}>Show all friends</button>*/}
                    <button className="actions" onClick={() => this.showAllFriends()}>Show
                        all friends
                    </button>
                    <button className="actions" onClick={() => this.showBirthdays()}>Show
                        birthdays
                    </button>
                    <button className="actions"
                            onClick={() => this.showPotentialFriends()}>Show potentials
                        friends
                    </button>
                    <button className="actions"
                            onClick={() => this.showUpcomingBirthdays()}>Show upcoming
                        birthdays
                    </button>
                </div>
                <div className="last">
                    {this.state.results}
                </div>
            </div>
        );
    }
}

export default Login;

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

    /**
     * Process results from the DB.
     *
     * @param url
     *  The address of the url.
     */
    processUsers(url) {
        let friends = [];
        var self = this;

        axios.get(settings.backend + '/' + url + '?uid=' + this.state.uid)
            .then((response) => {
                if (typeof response.data === 'object') {
                    Object.keys(response.data).forEach((item) => {
                        friends.push(response.data[item]);
                    });
                }
                else {
                    response.data.map((item) => {
                        friends.push(item);
                    });
                }

                self.setState({
                    results: <ul>{friends.map((item) => <li><User obj={item} currentUid={this.state.uid}/></li>)}</ul>
                });
            });
    }

    /**
     * Get all the friends.
     */
    showAllFriends() {
        this.processUsers('friends/show/all');
    }

    /**
     * Get friends with the nearest birthday.
     */
    showBirthdays() {
        this.processUsers('friends/show/birthdates');
    }

    /**
     * Get the potentials friends.
     */
    showPotentialFriends() {
        this.processUsers('friends/show/potentials');
    }

    /**
     * Get all the members which have an upcoming birthday.
     */
    showUpcomingBirthdays() {
        this.processUsers('friends/show/birthdays/upcoming');
    }

    render() {
        return (
            <div className="Homepage">
                <div className="first">
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

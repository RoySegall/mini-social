import React, {Component} from 'react';
import './Homepage.css';
import settings from './settings';

class Login extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
      results: <h2>Hello there! Please do something with the buttons on the
        left.</h2>
    };
  }

  showAllFriends() {
    let friends = ['Roy', 'Roy', 'John'];
    this.setState({
      results: <ul>{friends.map((item) => <li>{item}</li>)}</ul>
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

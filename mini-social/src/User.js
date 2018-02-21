import React, {Component} from 'react';

class User extends Component {

  constructor(props) {
    super(props);

    let friends = props.obj.friends;
    let isFriend = 'No';

    if (props.obj.friends !== undefined) {
      isFriend = friends.indexOf(props.currentUid) !== -1 ? 'Yes' : 'No';
    }

    this.state = {
      item: props.obj,
      currentUserId: props.currentUid,
      isFriend: isFriend,
    }
  }

  render() {
    return (
      <div className="UserItem">
          <b>Username: </b> {this.state.item.username},
          <br />
          <b>Friend of yours?</b>: {this.state.isFriend}
      </div>
    );
  }
}

export default User;

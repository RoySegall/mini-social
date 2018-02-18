<template>
  <div class="hello">
    <h1>{{ h1 }}</h1>

    <div id="login">
      <ul v-if="errors">
        <li v-for="error in errors">{{ error }}</li>
      </ul>
      <div class="element-wrapper username">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" v-model="username" placeholder="username" />
      </div>

      <div class="element-wrapper password">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" v-model="password" placeholder="password" />
      </div>

      <div class="element-wrapper submit">
        <button type="submit" id="submit" v-on:click="login()" value="Login!">Login!</button>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'login',
  data() {
    return {
      h1: 'Welcome to mini social. Please login first.',
      username: '',
      password: '',
      errors: [],
    };
  },
  methods: {
    login() {
      this.errors = [];

      if (this.username === '') {
        this.errors.push('The username is empty. Please fill it with some values.');
      }

      if (this.password === '') {
        this.errors.push('The password is empty. Please fill it with some values.');
      }

      if (this.errors.length === 0) {
        console.log('Login!');
      }
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

#login .element-wrapper {
  padding-bottom: 10px;
}

#login .element-wrapper label {
    font-size: 2em;
}

#login .element-wrapper input {
  height: 1.5em;
  font-size: 1.5em;
  margin-left: 0.5em;
  padding-left: 0.5em;
}

</style>

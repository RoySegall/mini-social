<template>
  <div id="app">
    <header>
      <router-link :to="{ name: 'home'}">Mini social</router-link>
    </header>
    <main>
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
export default {
  name: 'app',
};
</script>

<style lang="less" src="./assets/styles/app.less"></style>

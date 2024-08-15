<template>
  <div id="wrapper">
    <Navbar/>
    <Sidebar/>
    <div class="content-page">
      <router-view></router-view>
      <Footer/>
    </div>
  </div>
</template>
<script>
import Sidebar from './Sidebar'
import Footer from './Footer'
import Navbar from "./Navbar";

export default {
  components: {
    Navbar,
    Sidebar,
    Footer
  }
}
</script>

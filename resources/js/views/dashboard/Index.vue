<template>
  <div class="content">
    <div class="container-fluid">
      <div class="page-title-box">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h4 class="page-title">Dashboard</h4>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active">Welcome to SSR Global LLC</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- end row -->
      <div class="row">
<!--        <div class="col-xl-3 col-md-6">-->
<!--          <div class="card mini-stat bg-primary text-white">-->
<!--            <div class="card-body">-->
<!--              <div class="mb-4">-->
<!--                <div class="float-left mini-stat-img mr-4"><img src="assets/images/services-icon/01.png" alt="" /></div>-->
<!--                <h5 class="font-16 text-uppercase mt-0 text-white-50">Total Student</h5>-->
<!--                <h4 class="font-500">1,685 <i class="mdi mdi-arrow-up text-success ml-2"></i></h4>-->
<!--              </div>-->
<!--              <div class="pt-2">-->
<!--                <div class="float-right">-->
<!--                  <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>-->
<!--                </div>-->
<!--&lt;!&ndash;                <p class="text-white-50 mb-0">Since last month</p>&ndash;&gt;-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-xl-3 col-md-6">-->
<!--          <div class="card mini-stat bg-primary text-white">-->
<!--            <div class="card-body">-->
<!--              <div class="mb-4">-->
<!--                <div class="float-left mini-stat-img mr-4"><img src="assets/images/services-icon/02.png" alt="" /></div>-->
<!--                <h5 class="font-16 text-uppercase mt-0 text-white-50">Total Paid Amount</h5>-->
<!--                <h4 class="font-500">0 <i class="mdi mdi-arrow-down text-danger ml-2"></i></h4>-->
<!--              </div>-->
<!--              <div class="pt-2">-->
<!--                <div class="float-right">-->
<!--                  <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-xl-3 col-md-6">-->
<!--          <div class="card mini-stat bg-primary text-white">-->
<!--            <div class="card-body">-->
<!--              <div class="mb-4">-->
<!--                <div class="float-left mini-stat-img mr-4"><img src="assets/images/services-icon/03.png" alt="" /></div>-->
<!--                <h5 class="font-16 text-uppercase mt-0 text-white-50">Total Due Amount</h5>-->
<!--                <h4 class="font-500">0<i class="mdi mdi-arrow-up text-success ml-2"></i></h4>-->
<!--              </div>-->
<!--              <div class="pt-2">-->
<!--                <div class="float-right">-->
<!--                  <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="col-xl-3 col-md-6">-->
<!--          <div class="card mini-stat bg-primary text-white">-->
<!--            <div class="card-body">-->
<!--              <div class="mb-4">-->
<!--                <div class="float-left mini-stat-img mr-4"><img src="assets/images/services-icon/04.png" alt="" /></div>-->
<!--                <h5 class="font-16 text-uppercase mt-0 text-white-50">Total Hostel Bill</h5>-->
<!--                <h4 class="font-500">0 <i class="mdi mdi-arrow-up text-success ml-2"></i></h4>-->
<!--              </div>-->
<!--              <div class="pt-2">-->
<!--                <div class="float-right">-->
<!--                  <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
      </div>
    </div>
    <!-- container-fluid -->
  </div>
</template>
<script>
import {Common} from "../../mixins/common";

export default {
  mixins: [Common],
  data() {
    return {
      isLoading: true
    }
  },
  created() {
    // this.getData();
  },
  mounted() {
    document.title = 'Dashboard | SSR Global LLC';
  },
  methods: {
    getData() {
      // this.axiosGet('dashboard-data', (response) => {
      //    this.total_pending = response.total_pending;
      //    this.todays_order = response.todays_order;
      //    this.todays_amount = response.todays_amount;
      //    this.total_order = response.total_order;
      this.isLoading = false;
      // }, (error) => {
      //   this.errorNoti(error);
      // });
    }
  }
}
</script>


<style scoped>
.bg-blue {
  background: #626ed4!important;
  text-align: center;
  text-transform: uppercase;
}
.card-body {
  padding: 0.60rem !important;
}
.bg-blue span {
  font-size: 18px;
}
.task {
  background: #ff8c5e42;
  padding: 5px 8px;
  font-size: 14px;
  margin-bottom: 10px;
  border-radius: 5px;
  font-weight: bold;
}
.links {
  /*height: 148px;*/
}
.helpline {
  height: 120px;
  text-align: center;
  position: relative;
  background-image: linear-gradient(180deg,#ff8c5e8c,#ff8c5e47);
}
.helpline span {
  position: absolute;
  top: 40%;
  left: 0;
  right: 0;
  font-weight: bold;
  font-size: 20px;
  text-transform: uppercase;
  color: #2233c4;
}
.contact {
  height: 120px;
  text-align: center;
  padding-top: 20px;
  background-image: linear-gradient(180deg,#ff8c5e8c,#ff8c5e47);
}
.contact p {
  margin: 0;
  padding: 2px;
  font-weight: bold;
}
.header-bg {
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  text-transform: uppercase;
}
</style>

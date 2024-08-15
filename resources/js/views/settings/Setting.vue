<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Settings']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <form class="repeater" @submit.prevent="updateSetting" @keydown="form.onKeydown($event)">
                <div data-repeater-list="group-a">
                  <div class="row">
                    <div class="form-group col-lg-6">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" :class="{ 'is-invalid': form.errors.has('email') }" v-model="form.email" name="email">
                      <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                    </div>
                    <div class="form-group col-lg-6">
                      <label for="mobile">Mobile</label>
                      <input type="text" class="form-control" id="mobile" :class="{ 'is-invalid': form.errors.has('mobile') }" v-model="form.mobile" name="mobile">
                      <div class="error" v-if="form.errors.has('mobile')" v-html="form.errors.get('mobile')" />
                    </div>
                    <div class="form-group col-lg-6">
                      <label for="address">Address</label>
                      <input type="text" class="form-control" id="address" :class="{ 'is-invalid': form.errors.has('address') }" v-model="form.address" name="address">
                      <div class="error" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                    </div>
                    <div class="form-group col-lg-6">
                      <label for="opening_hour">Opening Hour</label>
                      <input type="text" class="form-control" id="opening_hour" :class="{ 'is-invalid': form.errors.has('opening_hour') }" v-model="form.opening_hour" name="opening_hour">
                      <div class="error" v-if="form.errors.has('opening_hour')" v-html="form.errors.get('opening_hour')" />
                    </div>
                    <div class="form-group col-lg-2" style="margin-top: 26px">
                      <button type="submit" class="btn btn-success mo-mt-2 float-left" value="Add Menu">Update Settings</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import {baseurl} from "../../base_url";

export default {
  name: "Setting",
  data() {
    return {
      form: new Form({
        id: '',
        email: '',
        mobile: '',
        address: '',
        opening_hour: '',
      }),
    }
  },
  mounted() {
    document.title = 'Setting | SSR Global LLC';
    this.getAllSetting();
  },
  methods: {
    getAllSetting(){
      this.isLoading = true;
      axios.get(baseurl + 'api/get-all-setting').then((response)=>{
        this.form.fill(response.data.setting);
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    updateSetting(){
      this.form.post('/api/update-setting').then((response)=>{
        console.log(response)
        this.$toaster.success(response.data.message);
      }).catch((error)=>{
        this.$toaster.error('Something went wrong')
      })
    },
  }
}
</script>

<style scoped>

</style>

<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Address Details']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center"  v-html ="addresses.details"></td>
                    </tr>
                    </tbody>
                  </table>
                  <br>

                </div>
              </div>
            </div>
            <div v-else>
              <skeleton-loader :row="14"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Details",
  data() {
    return {
      addresses: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAddress();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Address Details | SSR Construction';
    this.getAddress();
  },
  created(){
    this.getAddress();
  },
  methods: {
    getAddress(){
      this.isLoading = true;
      axios.get(`/api/address/${this.$route.params.id}`).then((response)=>{
        this.addresses = response.data.data
        this.isLoading = false;
      }).catch((error)=>{

      })
    },


    reload(){
      this.query = "";
      this.getAddress();
      this.$toaster.success('Data Successfully Refresh');
    },

  },
}
</script>

<style lang="scss" scoped>

</style>

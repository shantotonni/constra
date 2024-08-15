<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Page Details']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center" v-html="pages.body"></td>
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
        pages: [],
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
        this.getPageDetails();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Page Details | SSR Global LLC';
    this.getPageDetails();
  },
  created(){
     this.getPageDetails();
  },
    methods: {
        getPageDetails(){
            this.isLoading = true;
            axios.get(`/api/page-details/${this.$route.params.id}`).then((response)=>{
                console.log(response);
                this.pages = response.data.data
                this.isLoading = false;
            }).catch((error)=>{

            })
        },


        reload(){
            this.query = "";
            this.getPageDetails();
            this.$toaster.success('Data Successfully Refresh');
        },

    },
}
</script>

<style lang="scss" scoped>

</style>

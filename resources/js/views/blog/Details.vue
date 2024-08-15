<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['News Details']"/>
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
                      <td class="text-center" v-html="blogs.description"></td>
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
      blogs: [],
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
        this.getBlogDetails();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'News Details | Ashar Immigration';
    this.getBlogDetails();
  },
  created(){
    this.getBlogDetails();
  },
  methods: {
    getBlogDetails(){
      this.isLoading = true;
      axios.get(`/api/news-details/${this.$route.params.id}`).then((response)=>{
        this.blogs = response.data.data
        this.isLoading = false;
      }).catch((error)=>{

      })
    },


    reload(){
      this.query = "";
      this.getBlogDetails();
      this.$toaster.success('Data Successfully Refresh');
    },

  },
}
</script>

<style lang="scss" scoped>

</style>

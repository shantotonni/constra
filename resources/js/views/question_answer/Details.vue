<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Question Answer']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>Short Introduction</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td class="text-center"  v-html ="chooses.short_intro"></td>
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
      chooses: [],
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
        this.getQuestionAnswer();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Question Answer | SSR Global LLC';
    this.getQuestionAnswer();
  },
  created(){
    this.getQuestionAnswer();
  },
  methods: {
    getQuestionAnswer(){
      this.isLoading = true;
      axios.get(`/api/why-choose-us-details/${this.$route.params.id}`).then((response)=>{
        this.chooses = response.data.data
        this.isLoading = false;
      }).catch((error)=>{

      })
    },


    reload(){
      this.query = "";
      this.getQuestionAnswer();
      this.$toaster.success('Data Successfully Refresh');
    },

  },
}
</script>

<style lang="scss" scoped>

</style>

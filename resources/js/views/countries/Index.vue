<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Countries']"/>
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="datatable" v-if="!isLoading">
              <div class="card-body">
                <div class="d-flex">
                  <div class="flex-grow-1">
                    <div class="row">
                      <div class="col-md-2">
                        <input v-model="query" type="text" class="form-control" placeholder="Search">
                      </div>
                    </div>
                  </div>
                  <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" @click="createQnA">
                      <i class="fas fa-plus"></i>
                      Add Country
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" @click="reload">
                      <i class="fas fa-sync"></i>
                      Reload
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                      <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(country, i) in countries"
                        :key="country.id"
                        v-if="countries.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left" v-html = "country.name"></td>
                      <td class="text-center" >
                        <button @click="edit(country)" class="btn btn-success btn-sm">
                          <i class="far fa-edit"></i></button>
                        <button @click="destroy(country.id)"
                                class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                        </button>
                      </td>
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
    <!--  Modal content for the above example -->
    <div class="modal fade" id="QnAModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Country</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name</label>
                      <input  type="text" name="name" step="2" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Countries</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {baseurl} from '../../base_url'
import {VueEditor} from "vue2-editor";

export default {
  components: {
    VueEditor
  },
  data() {
    return {
      countries: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        name :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllQnA();
      } else {
        this.searchData();
      }
    },
    AreaData: (val) => {
      this.form.body = $('.summernote').summernote("code", val);
    }
  },
  mounted() {
    document.title = 'Countries | Ashar Immigration';
    this.getAllQnA();
  },
  methods: {
    getAllQnA(){
      this.isLoading = true;
      axios.get(baseurl + 'api/countries?page='+ this.pagination.current_page).then((response)=>{
        this.countries = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/countries/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.countries = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllQnA();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#QnAModal").modal("hide");
    },
    createQnA(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#QnAModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/countries").then(response => {
        $("#QnAModal").modal("hide");
        this.getAllQnA();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(country) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(country);
      $("#QnAModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/countries/" + this.form.id).then(response => {
        $("#QnAModal").modal("hide");
        this.getAllQnA();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    destroy(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete('api/countries/' + id).then((response) => {
            this.getAllQnA();
            Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
          })
        }
      })
    }
  },
}
</script>

<style scoped>

</style>

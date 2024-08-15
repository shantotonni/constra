<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Page']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createPage">
                      <i class="fas fa-plus"></i>
                      Add Page
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
                        <th>Page Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(page, i) in pages"
                        :key="page.id"
                        v-if="pages.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ page.title }}</td>
                      <td class="text-left">{{ page.slug }}</td>
                      <td class="text-left">{{ page.status }}</td>
                      <td class="text-center">
                        <router-link :to="`page-details/${page.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>
                        <button @click="edit(page)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(page.id)"
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
    <div class="modal fade" id="PageModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Page</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Page Name</label>
                      <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                      <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select name="status" id="" v-model="form.status" class="form-control">
                        <option value="Y">Active</option>
                        <option value="N">Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('status')" v-html="form.errors.get('status')" />
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Body</label>
                        <vue-editor name="body" v-model="form.body" :class="{ 'is-invalid': form.errors.has('body') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('body')" v-html="form.errors.get('body')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Page</button>
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
      pages: [],
      pagination: {
        current_page: 1
      },
      AreaData : '',
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        title :'',
        body :'',
        status :'Y',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllPage();
      } else {
        this.searchData();
      }
    },
    AreaData: (val) => {
      this.form.body = $('.summernote').summernote("code", val);
    }
  },
  mounted() {
    document.title = 'Page List | SSR Global LLC';
    this.getAllPage();
  },
  methods: {
    getAllPage(){
      this.isLoading = true;
      axios.get(baseurl + 'api/pages?page='+ this.pagination.current_page).then((response)=>{
        this.pages = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/pages/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.pages = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllPage();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#PageModal").modal("hide");
    },
    createPage(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#PageModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/pages").then(response => {
        $("#PageModal").modal("hide");
        this.getAllPage();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(page) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(page);
      $("#PageModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/pages/" + this.form.id).then(response => {
        $("#PageModal").modal("hide");
        this.getAllPage();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    changeImage(event) {
      let file = event.target.files[0];
      let reader = new FileReader();
      reader.onload = event => {
        this.form.image = event.target.result;
      };
      reader.readAsDataURL(file);
    },
    showImage() {
      let img = this.form.image;
      if (img.length > 100) {
        return this.form.image;
      } else {
        return window.location.origin + "/images/page/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/page/" + image;
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
          axios.delete('api/pages/' + id).then((response) => {
            this.getAllPage();
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

<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Why Choose Us']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createChoose">
                      <i class="fas fa-plus"></i>
                      Add Why Choose Us
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
                        <th>Title</th>
                        <th>Icon</th>
                        <th>Description</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(choose, i) in chooses" :key="choose.id" v-if="chooses.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ choose.title }}</td>
                      <td class="text-left">{{ choose.icon }}</td>
                      <td class="text-left" v-html="choose.short_intro"></td>
                      <td class="text-center">
                        <router-link :to="`why-choose-us-details/${choose.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>
                        <button @click="edit(choose)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                        <button @click="destroy(choose.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
    <div class="modal fade" id="ChooseModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Why Choose Us</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title</label>
                      <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                      <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Icon</label>
                      <input type="text" name="icon" v-model="form.icon" class="form-control" :class="{ 'is-invalid': form.errors.has('icon') }">
                      <div class="error" v-if="form.errors.has('icon')" v-html="form.errors.get('icon')" />
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Short Introduction</label>
                        <vue-editor name="short intro" v-model="form.short_intro" :class="{ 'is-invalid': form.errors.has('short_intro') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('short_intro')" v-html="form.errors.get('short_intro')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Why Choose Us</button>
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
      chooses: [],
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
        icon :'',
        short_intro :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllChoose();
      } else {
        this.searchData();
      }
    },
    AreaData: (val) => {
      this.form.body = $('.summernote').summernote("code", val);
    }
  },
  mounted() {
    document.title = 'Why Choose Us List | SSR Global LLC';
    this.getAllChoose();
  },
  methods: {
    getAllChoose(){
      this.isLoading = true;
      axios.get(baseurl + 'api/why-choose-us?page='+ this.pagination.current_page).then((response)=>{
        this.chooses = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/why-choose-us/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.chooses = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllChoose();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#ChooseModal").modal("hide");
    },
    createChoose(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#ChooseModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/why-choose-us").then(response => {
        $("#ChooseModal").modal("hide");
        this.getAllChoose();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(choose) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(choose);
      $("#ChooseModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/why-choose-us/" + this.form.id).then(response => {
        $("#ChooseModal").modal("hide");
        this.getAllChoose();
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
          axios.delete('api/why-choose-us/' + id).then((response) => {
            this.getAllChoose();
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

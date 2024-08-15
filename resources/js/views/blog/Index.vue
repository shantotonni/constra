<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['News List']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createBlog">
                      <i class="fas fa-plus"></i>
                      Add News
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
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(blog, i) in blogs"
                        :key="blog.id"
                        v-if="blogs.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ blog.title }}</td>
                      <td class="text-center">
                        <img v-if="blog.image" height="40" width="40"
                             :src="tableImage(blog.image)" alt="">
                      </td>
                      <td class="text-left">{{ blog.status }}</td>

                      <td class="text-center">
                        <router-link :to="`news-details/${blog.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>

                        <button @click="edit(blog)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(blog.id)"
                                class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
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
    <div class="modal fade" id="BlogModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} News</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>News Name</label>
                      <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                      <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select type="Status" name="status" v-model="form.status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                        <option disabled value="">Select Status</option>
                        <option >Active</option>
                        <option >Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('status')" v-html="form.errors.get('status')"/>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                      <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                      <p style="font-size: 12px">Image Size : Width-407px, Height- 270px</p>
                      <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')"/>
                      <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>description</label>
                        <vue-editor name="description" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('description')" v-html="form.errors.get('description')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} News</button>
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
      blogs: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        title :'',
        description :'',
        image :'',
        status :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllBlog();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Our News List | SSR Global LLC';
    this.getAllBlog();
  },
  methods: {
    getAllBlog(){
      this.isLoading = true;
      axios.get(baseurl + 'api/news?page='+ this.pagination.current_page).then((response)=>{
        this.blogs = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/news/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.blogs = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllBlog();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#BlogModal").modal("hide");
    },
    createBlog(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#BlogModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/news").then(response => {
        if (response.data.status === 'error'){
          this.$toaster.error(response.data.message);
        }else {
          this.$toaster.success(response.data.message);
          $("#BlogModal").modal("hide");
          this.getAllBlog();
        }
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(blog) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(blog);
      $("#BlogModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/news/" + this.form.id).then(response => {
        if (response.data.status === 'error'){
          this.$toaster.error(response.data.message);
        }else {
          this.$toaster.success(response.data.message);
          $("#BlogModal").modal("hide");
          this.getAllBlog();
        }
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
        return window.location.origin + "/images/blog/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/blog/" + image;
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
          axios.delete('api/news/' + id).then((response) => {
            this.getAllBlog();
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

<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Testimonial']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createTestimonial">
                      <i class="fas fa-plus"></i>
                      Add Testimonial
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
                        <th>Testimonial Name</th>
                        <th>Position </th>
                        <th>Image</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(testimonial, i) in testimonials" :key="testimonial.id" v-if="testimonials.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ testimonial.name }}</td>
                      <td class="text-left">{{ testimonial.position }}</td>
                      <td class="text-center">
                      <img v-if="testimonial.image" height="40" width="40"
                           :src="tableImage(testimonial.image)" alt="">
                    </td>
                      <td class="text-center">
                        <button @click="edit(testimonial)" class="btn btn-success btn-sm">
                          <i
                              class="far fa-edit"></i></button>
                        <button @click="destroy(testimonial.id)"
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
    <div class="modal fade" id="TestimonialModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Testimonial</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Testimonial Name</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Position</label>
                      <input type="text" name="position" v-model="form.position" class="form-control" :class="{ 'is-invalid': form.errors.has('position') }">
                      <div class="error" v-if="form.errors.has('position')" v-html="form.errors.get('position')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                      <input @change="changeImage($event)" type="file" name="image"
                             class="form-control"
                             :class="{ 'is-invalid': form.errors.has('image') }">
                      <div class="error" v-if="form.errors.has('image')"
                           v-html="form.errors.get('image')"/>
                      <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px"
                           width="40px">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Complement</label>
                        <vue-editor name="complement" v-model="form.complement" :class="{ 'is-invalid': form.errors.has('complement') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('complement')" v-html="form.errors.get('complement')"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Testimonial</button>
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
      programs: [],
      testimonials: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        name :'',
        complement :'',
        position :'',
        image :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllTestimonial();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Testimonial List | SSR Global LLC';
    this.getAllTestimonial();
  },
  methods: {
    getAllTestimonial(){
      this.isLoading = true;
      axios.get(baseurl + 'api/testimonial?page='+ this.pagination.current_page).then((response)=>{
        this.testimonials = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/testimonial/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.testimonials = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllTestimonial();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#TestimonialModal").modal("hide");
    },
    createTestimonial(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#TestimonialModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/testimonial").then(response => {
        $("#TestimonialModal").modal("hide");
        this.getAllTestimonial();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(testimonial) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(testimonial);
      $("#TestimonialModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/testimonial/" + this.form.id).then(response => {
        $("#TestimonialModal").modal("hide");
        this.getAllTestimonial();
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
        return window.location.origin + "/images/testimonial/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/testimonial/" + image;
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
          axios.delete('api/testimonial/' + id).then((response) => {
            this.getAllTestimonial();
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

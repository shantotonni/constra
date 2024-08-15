<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Gallery']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createSlider">
                      <i class="fas fa-plus"></i>
                      Add Gallery
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
                        <th>Slider Name</th>
                        <th>Image</th>
                        <th>Order</th>
                        <th>Video Status</th>
                        <th>Video Link</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(slider, i) in sliders" :key="slider.id" v-if="sliders.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-center">{{ slider.title }}</td>
                      <td class="text-center">
                        <img v-if="slider.image" height="40" width="40" :src="tableImage(slider.image)" alt="">
                      </td>
                      <td class="text-center">{{ slider.ordering }}</td>
                      <td class="text-center">{{ slider.video_status }}</td>
                      <td class="text-center">{{ slider.link }}</td>
                      <td class="text-center">{{ slider.status }}</td>
                      <td class="text-center">
<!--                        <router-link :to="`slider-details/${slider.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>-->
                        <button @click="edit(slider)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                        <button @click="destroy(slider.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
    <div class="modal fade" id="SliderModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Gallery</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Slider Name</label>
                      <input type="text" name="title" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                      <div class="error" v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Ordering</label>
                      <input type="number" name="ordering" v-model="form.ordering" class="form-control" :class="{ 'is-invalid': form.errors.has('ordering') }">
                      <div class="error" v-if="form.errors.has('ordering')" v-html="form.errors.get('ordering')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select name="status" v-model="form.status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                        <option disabled value="">Select Status</option>
                        <option value="Y">Active</option>
                        <option value="N">Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('status')" v-html="form.errors.get('status')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Video</label>
                      <select name="status" v-model="form.video_status" class="form-control" :class="{ 'is-invalid': form.errors.has('video_status') }" @change="videoStatus">
                        <option disabled value="">Select Status</option>
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                      </select>
                      <div class="error" v-if="form.errors.has('video_status')" v-html="form.errors.get('video_status')"/>
                    </div>
                  </div>

                  <div class="col-md-6" v-if="isLinkShow">
                    <div class="form-group">
                      <label>Link</label>
                      <input type="text" name="link" v-model="form.link" class="form-control" :class="{ 'is-invalid': form.errors.has('link') }">
                      <div class="error" v-if="form.errors.has('link')" v-html="form.errors.get('link')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                      <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                      <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')"/>
                      <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Paragraph</label>
                        <vue-editor name="paragraph" v-model="form.paragraph" :class="{ 'is-invalid': form.errors.has('paragraph') }"></vue-editor>
                        <div class="error" v-if="form.errors.has('paragraph')" v-html="form.errors.get('paragraph')"/>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Gallery</button>
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
      sliders: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        title :'',
        paragraph :'',
        link :'',
        image :'',
        status :'',
        ordering :'',
        video_status :'',
      }),
      isLinkShow:false
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllSlider();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Gallery List | SSR Global LLC';
    this.getAllSlider();
  },
  methods: {
    getAllSlider(){
      this.isLoading = true;
      axios.get(baseurl + 'api/gallery?page='+ this.pagination.current_page).then((response)=>{
        this.sliders = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/gallery/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.sliders = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllSlider();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#SliderModal").modal("hide");
    },
    createSlider(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#SliderModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/gallery").then(response => {
        $("#SliderModal").modal("hide");
        this.getAllSlider();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(slider) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(slider);
      $("#SliderModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/gallery/" + this.form.id).then(response => {
        $("#SliderModal").modal("hide");
        this.getAllSlider();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    videoStatus(){
      if (this.form.video_status === 'Y'){
        this.isLinkShow = true
      }else {
        this.isLinkShow = false
      }
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
        return window.location.origin + "/images/gallery/" + this.form.image;
      }
    },
    tableImage(image) {
      return window.location.origin + "/images/gallery/" + image;
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
          axios.delete('api/gallery/' + id).then((response) => {
            this.getAllSlider();
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

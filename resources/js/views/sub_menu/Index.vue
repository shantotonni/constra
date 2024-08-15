<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Web Menu Sub']"/>
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
                    <button type="button" class="btn btn-success btn-sm" @click="createsubmenu">
                      <i class="fas fa-plus"></i>
                      Add Sub Menu
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
                      <th>Web Menu </th>
                      <th>Web Sub Menu </th>
                      <th>Web Icon</th>
                      <th>Web URL</th>
                      <th>Slug</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(submenu, i) in submenus" :key="submenu.id" v-if="submenus.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ submenu.menu_name }}</td>
                      <td class="text-left">{{ submenu.name }}</td>
                      <td class="text-left">{{ submenu.icon }}</td>
                      <td class="text-left">{{ submenu.url }}</td>
                      <td class="text-left">{{ submenu.slug }}</td>
                      <td class="text-left">{{ submenu.active }}</td>
                      <td class="text-center">
                        <button @click="edit(submenu)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                        <button @click="destroy(submenu.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllWebSubMenu() : searchData()"
                  ></pagination>
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
    <div class="modal fade" id="submenuModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Submenu</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Menu</label>
                      <select name="menu_id" id="menu_id" class="form-control" v-model="form.menu_id" :class="{ 'is-invalid': form.errors.has('menu_id') }">
                        <option disabled value="">Select Menu</option>
                        <option :value="webmenu.id" v-for="(webmenu , index) in webmenus" :key="index">{{ webmenu.name }}
                        </option>
                      </select>
                      <div class="error" v-if="form.errors.has('menu_id')" v-html="form.errors.get('menu_id')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Web Sub menu</label>
                      <input type="text" name="name" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <div class="error" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Web Icon</label>
                      <input type="text" name="icon" v-model="form.icon" class="form-control" :class="{ 'is-invalid': form.errors.has('icon') }">
                      <div class="error" v-if="form.errors.has('icon')" v-html="form.errors.get('icon')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Status</label>
                      <select type="active" name="active" v-model="form.active" class="form-control" :class="{ 'is-invalid': form.errors.has('active') }">
                        <option disabled value="">Select Status</option>
                        <option value="Y">Active</option>
                        <option value="N">Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('active')" v-html="form.errors.get('active')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>URL</label>
                      <input type="text" class="form-control" id="url" :class="{ 'is-invalid': form.errors.has('url') }" v-model="form.url" name="url" placeholder="url">
                      <div class="error" v-if="form.errors.has('url')" v-html="form.errors.get('url')" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Sub Menu Order</label>
                      <input type="number" name="ordering" v-model="form.ordering" class="form-control" :class="{ 'is-invalid': form.errors.has('ordering') }">
                      <div class="error" v-if="form.errors.has('ordering')" v-html="form.errors.get('ordering')" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Sub menu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {baseurl} from '../../base_url'

export default {
  data() {
    return {
      webmenus: [],
      submenus: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        name :'',
        icon :'',
        active :'',
        url :'',
        menu_id :'',
        ordering :'',
      }),
    }
  },
  watch: {
    query: function(newQ, old) {
      if (newQ === "") {
        this.getAllWebSubMenu();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Web Menu | Ashar Immigration';
    this.getAllWebSubMenu();
  },
  methods: {
    getAllWebSubMenu(){
      this.isLoading = true;
      axios.get(baseurl + 'api/web-sub-menu?page='+ this.pagination.current_page).then((response)=>{
        this.submenus = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    getAllWebMenu() {
      axios.get('/api/web-menu').then((response) => {
        console.log(response)
        this.webmenus = response.data.data;
      }).catch((error) => {

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/web-sub-menu/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.submenus = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload(){
      this.getAllWebSubMenu();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal(){
      $("#submenuModal").modal("hide");
    },
    createsubmenu(){
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      this.getAllWebMenu();
      $("#submenuModal").modal("show");
    },
    store(){
      this.form.busy = true;
      this.form.post(baseurl + "api/web-sub-menu").then(response => {
        $("#submenuModal").modal("hide");
        this.getAllWebSubMenu();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(submenu) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(submenu);
      this.getAllWebMenu();
      $("#submenuModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/web-sub-menu/" + this.form.id).then(response => {
        $("#submenuModal").modal("hide");
        this.getAllWebSubMenu();
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
          axios.delete('api/web-sub-menu/' + id).then((response) => {
            this.getAllWebSubMenu();
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

<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Customer List']"/>
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
<!--                    <button type="button" class="btn btn-success btn-sm" @click="createCustomer">-->
<!--                      <i class="fas fa-plus"></i>-->
<!--                      Add Customer-->
<!--                    </button>-->
                    <button type="button" class="btn btn-success btn-sm" @click="exportCustomer">
                      <i class="fas fa-plus"></i>
                      Export
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" @click="reload">
                      <i class="fas fa-sync"></i>
                      Reload
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table
                      class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                    <thead>
                    <tr>
                      <th>SN</th>
                      <th>Customer Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Address</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(customer, i) in customers"
                        :key="customer.id"
                        v-if="customers.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-center">{{ customer.name }}</td>
                      <td class="text-center">{{ customer.email }}</td>
                      <td class="text-center">{{ customer.phone }}</td>
                      <td class="text-center">{{ customer.address }}</td>
                      <td class="text-center">{{ customer.customer_status }}</td>
                      <td class="text-center">
                        <button @click="edit(customer)" class="btn btn-success btn-sm"><i class="far fa-edit"></i></button>
                        <button @click="destroy(customer.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <pagination
                      v-if="pagination.last_page > 1"
                      :pagination="pagination"
                      :offset="5"
                      @paginate="query === '' ? getAllcustomer() : searchData()"
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
    <div class="modal fade" id="CustomerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Customer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>First Name</label>
                      <input type="text" name="first_name" v-model="form.first_name" class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                      <div class="error" v-if="form.errors.has('first_name')" v-html="form.errors.get('first_name')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Last Name</label>
                      <input type="text" name="last_name" v-model="form.last_name" class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                      <div class="error" v-if="form.errors.has('last_name')" v-html="form.errors.get('last_name')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                      <div class="error" v-if="form.errors.has('email')" v-html="form.errors.get('email')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Phone Number</label>
                      <input type="phone" name="phone" v-model="form.phone" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                      <div class="error" v-if="form.errors.has('phone')" v-html="form.errors.get('phone')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Age of Children</label>
                      <input type="number" name="ages_of_children" v-model="form.ages_of_children" class="form-control" :class="{ 'is-invalid': form.errors.has('ages_of_children') }">
                      <div class="error" v-if="form.errors.has('ages_of_children')" v-html="form.errors.get('ages_of_children')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Age of Father</label>
                      <input type="number" name="ages_of_father" v-model="form.ages_of_father" class="form-control" :class="{ 'is-invalid': form.errors.has('ages_of_father') }">
                      <div class="error" v-if="form.errors.has('ages_of_father')" v-html="form.errors.get('ages_of_father')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Want to receive email</label>
                      <select type="Status" name="want_to_receive_email" v-model="form.want_to_receive_email" class="form-control" :class="{ 'is-invalid': form.errors.has('want_to_receive_email') }">
                        <option disabled value="">Select Option</option>
                        <option>Yes</option>
                        <option>No</option>
                      </select>
                      <div class="error" v-if="form.errors.has('want_to_receive_email')" v-html="form.errors.get('want_to_receive_email')"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Customer Status</label>
                      <select type="Status" name="customer_status" v-model="form.customer_status" class="form-control" :class="{ 'is-invalid': form.errors.has('customer_status') }">
                        <option disabled value="">Select Option</option>
                        <option>Active</option>
                        <option>Inactive</option>
                      </select>
                      <div class="error" v-if="form.errors.has('customer_status')" v-html="form.errors.get('customer_status')"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }}
                Customer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <data-export/>
  </div>
</template>

<script>
import {bus} from "../../app";
import {baseurl} from '../../base_url'
import {VueEditor} from "vue2-editor";

export default {
  components: {
    VueEditor
  },
  data() {
    return {
      customers: [],
      pagination: {
        current_page: 1
      },
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id: '',
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        ages_of_children: '',
        ages_of_father: '',
        want_to_receive_email: '',
        customer_status: '',
      }),
    }
  },
  watch: {
    query: function (newQ, old) {
      if (newQ === "") {
        this.getAllcustomer();
      } else {
        this.searchData();
      }
    }
  },
  mounted() {
    document.title = 'Customer List | Ashar Immigration';
    this.getAllcustomer();
  },
  methods: {
    getAllcustomer() {
      this.isLoading = true;
      axios.get(baseurl + 'api/customers?page=' + this.pagination.current_page).then((response) => {
        this.customers = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error) => {

      })
    },
    searchData() {
      axios.get(baseurl + "api/search/customers/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.customers = response.data.data;
        this.pagination = response.data.meta;
      }).catch(e => {
        this.isLoading = false;
      });
    },
    reload() {
      this.getAllcustomer();
      this.query = "";
      this.$toaster.success('Data Successfully Refresh');
    },
    closeModal() {
      $("#CustomerModal").modal("hide");
    },
    createCustomer() {
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#CustomerModal").modal("show");
    },
    store() {
      this.form.busy = true;
      this.form.post(baseurl + "api/customers").then(response => {
        $("#CustomerModal").modal("hide");
        this.getAllcustomer();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(customer) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(customer);
      $("#CustomerModal").modal("show");
    },
    update() {
      this.form.busy = true;
      this.form.put(baseurl + "api/customers/" + this.form.id).then(response => {
        $("#CustomerModal").modal("hide");
        this.getAllcustomer();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    exportCustomer(){
      axios.get(baseurl + 'api/export-customer')
          .then((response)=>{
            let dataSets = response.data.data;
            console.log(dataSets)
            if (dataSets.length > 0) {
              let columns = Object.keys(dataSets[0]);
              columns = columns.filter((item) => item !== 'row_num');
              let rex = /([A-Z])([A-Z])([a-z])|([a-z])([A-Z])/g;
              columns = columns.map((item) => {
                let title = item.replace(rex, '$1$4 $2$3$5')
                return {title, key: item}
              });
              bus.$emit('data-table-import', dataSets, columns, 'Customer List')
            }
          }).catch((error)=>{
      })
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
          axios.delete('api/customers/' + id).then((response) => {
            this.getAllcustomer();
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

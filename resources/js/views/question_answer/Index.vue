<template>
  <div class="content">
    <div class="container-fluid">
      <breadcrumb :options="['Q&A']"/>
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
                      Add Q&A
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
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(quest, i) in quests"
                        :key="quest.id"
                        v-if="quests.length">
                      <th class="text-center" scope="row">{{ ++i }}</th>
                      <td class="text-left">{{ quest.question }}</td>
                      <td class="text-left" v-html = "quest.answer"></td>
                      <td class="text-center" >
                        <button @click="edit(quest)" class="btn btn-success btn-sm">
                          <i class="far fa-edit"></i></button>
                        <button @click="destroy(quest.id)"
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
            <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Q&A</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">×</button>
          </div>
          <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)" >
            <div class="modal-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Question</label>
                      <input type="text" name="question" v-model="form.question" class="form-control" :class="{ 'is-invalid': form.errors.has('question') }">
                      <div class="error" v-if="form.errors.has('question')" v-html="form.errors.get('question')" />
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Answer</label>
                      <vue-editor name="answer" v-model="form.answer" :class="{ 'is-invalid': form.errors.has('answer') }"></vue-editor>
                      <div class="error" v-if="form.errors.has('answer')" v-html="form.errors.get('answer')"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
              <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Q&A</button>
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
      quests: [],
      pagination: {
        current_page: 1
      },
      AreaData : '',
      query: "",
      editMode: false,
      isLoading: false,
      form: new Form({
        id :'',
        question :'',
        answer :'',
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
    document.title = 'Q&A List | SSR Global LLC';
    this.getAllQnA();
  },
  methods: {
    getAllQnA(){
      this.isLoading = true;
      axios.get(baseurl + 'api/question-answer?page='+ this.pagination.current_page).then((response)=>{
        this.quests = response.data.data;
        this.pagination = response.data.meta;
        this.isLoading = false;
      }).catch((error)=>{

      })
    },
    searchData(){
      axios.get(baseurl + "api/search/question-answer/" + this.query + "?page=" + this.pagination.current_page).then(response => {
        this.quests = response.data.data;
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
      this.form.post(baseurl + "api/question-answer").then(response => {
        $("#QnAModal").modal("hide");
        this.getAllQnA();
      }).catch(e => {
        this.isLoading = false;
      });
    },
    edit(quest) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.form.fill(quest);
      $("#QnAModal").modal("show");
    },
    update(){
      this.form.busy = true;
      this.form.put(baseurl + "api/question-answer/" + this.form.id).then(response => {
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
          axios.delete('api/question-answer/' + id).then((response) => {
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

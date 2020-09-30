<template>
    <div class="container">
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Departments List</h3>

                <div class="card-tools">
                 <button class="btn btn-success" @click="newModal"> Add New <i class="fa fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="department in departments" :key="department.id">
                      <td>{{department.id}}</td>
                      <td>{{department.dep_name}}</td>
                      <td>
                          <a href="http://" @click.prevent="editModal(department)">
                            <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="" @click.prevent="deleteDepartment(department.id)">
                            <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                   
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

<!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Department's Info</h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form @submit.prevent="editmode ? updateDepartment() : createDepartment()">
        <div class="modal-body">
          <div class="form-group">
              <input v-model="form.dep_name" type="text" name="dep_name"
                  placeholder="Department Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('dep_name') }">
              <has-error :form="form" field="dep_name"></has-error>
          </div>
         
        </div>
        <div class="modal-footer">
          <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
          <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>

    </div>
  </div>
</div>

    </div>
</template>

<script>
import Form from 'vform'; 

    export default {
        data() {
            return {
                editmode: false,
                departments: {},
                form: new Form({
                    id: '',
                    dep_name: ''
                })
            }
        },
        methods: {
          updateDepartment() {
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/department/'+this.form.id)
            .then(() => {
              // success
              $('#addNew').modal('hide');
               swal.fire(
                    'Updated!',
                    'Department has been updated successfully!.',
                    'success'
                    )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.start();
            });

          },

          editModal(department) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(department);
          },

          newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },

          deleteDepartment(id){
            swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

               // Send request to the server
              if (result.value) {
                    this.form.delete('api/department/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'department has been deleted.',
                            'success'
                            )
                        Fire.$emit('AfterCreate');
                    }).catch(()=> {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                  }
              })
            },

          loadDepartments(){
            axios.get('api/department').then(({data}) => (this.departments = data.data));
          },

          createDepartment(){
            this.$Progress.start();
            this.form.post('api/department')
             .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'department Created Successfully!'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
            this.loadDepartments();
              Fire.$on('AfterCreate',() => {
               this.loadDepartments();
              });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

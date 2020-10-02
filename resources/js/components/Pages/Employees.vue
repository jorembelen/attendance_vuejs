<template>
    <div class="container">
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Employees List</h3>

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
                      <th>Department</th>
                      <th>Position</th>
                      <th>Late Check In</th>
                      <th>Early Check Out</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="employee in employees" :key="employee.uuid">
                      <td>{{employee.uuid}}</td>
                      <td>{{employee.name}}</td>
                      <td>{{employee.department}}</td>
                       <td>{{employee.position}}</td>
                       <td>{{employee.checkIn}}</td>
                       <td>{{employee.checkOut}}</td>
                      <td>
                          <a href="http://" @click.prevent="editModal(employee)">
                            <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="" @click.prevent="deleteEmployee(employee.uuid)">
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
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Employee's Info</h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form @submit.prevent="editmode ? updateEmployee() : createEmployee()">
        <div class="modal-body">
          <div class="form-group">
              <input v-model="form.name" type="text" name="name"
                  placeholder="Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
              <has-error :form="form" field="name"></has-error>
          </div>
          <div class="form-group">
              <select type="text" name="department" id="department" v-model="form.department"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('department') }">
                  <option value="">Select Department</option>
                  <option v-for="department in departments"
                   :key="department.dep_name">{{department.dep_name}}
                   </option>
                  </select>
              <has-error :form="form" field="department"></has-error>
          </div>
           <div class="form-group">
              <select v-model="form.position" type="text" name="position"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('position') }">
                  <option value="">Select Position</option>
                  <option v-for="position in positions"
                   :key="position.pos_name">{{position.pos_name}}
                   </option>
                  </select>
              <has-error :form="form" field="position"></has-error>
          </div>
           <div class="form-group">
              <input v-model="form.location" type="text" name="location"
                  placeholder="Location"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('location') }">
              <has-error :form="form" field="location"></has-error>
          </div>
           <div class="form-group">
              <input v-model="form.mobile" type="text" name="mobile"
                  placeholder="Mobile"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('mobile') }">
              <has-error :form="form" field="mobile"></has-error>
          </div>
            <div class="form-group">
              <select v-model="form.checkIn" name="checkIn"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('checkIn') }">
                  <option value="">Select Late Time In Rule</option>
                   <option v-for="checkIn in checkIns"
                   :key="checkIn.checkIn_time">{{checkIn.checkIn_time}}
                   </option>
                  </select>
              <has-error :form="form" field="checkIn"></has-error>
          </div>
          <div class="form-group">
              <select v-model="form.checkOut" type="text" name="checkOut"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('checkOut') }">
                  <option value="">Select Early Time Out Rule</option>
                   <option v-for="checkOut in checkOuts"
                   :key="checkOut.checkOut_time">{{checkOut.checkOut_time}}
                   </option>
                  </select>
              <has-error :form="form" field="checkOut"></has-error>
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
                departments: null,
                positions: null,
                checkIns: null,
                checkOuts: null,
                editmode: false,
                employees: {},
                form: new Form({
                    id: '',
                    uuid: '',
                    name: '',
                    department: '',
                    location: '',
                    contact: '',
                    position: '',
                    checkIn: '',
                    checkOut: ''
                })
            }
        },
        mounted(){
            axios.get('api/department').then(response => {
              this.departments = response.data.data;
            }),
             axios.get('api/position').then(response => {
              this.positions = response.data.data;
            }),
             axios.get('api/checkIn').then(response => {
              this.checkIns = response.data.data;
            }),
             axios.get('api/checkOut').then(response => {
              this.checkOuts = response.data.data;
            })
        },
      
        methods: {
          updateEmployee() {
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/employee/'+this.form.uuid)
            .then(() => {
              // success
              $('#addNew').modal('hide');
               swal.fire(
                    'Updated!',
                    'Employee has been updated successfully!.',
                    'success'
                    )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.start();
            });

          },

          editModal(employee) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(employee);
          },

          newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },

          deleteEmployee(uuid){
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
                    this.form.delete('api/employee/'+uuid).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Employee has been deleted successfully.',
                            'success'
                            )
                        Fire.$emit('AfterCreate');
                    }).catch(()=> {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                  }
              })
            },

          loadEmployees(){
            axios.get('api/employee').then(({data}) => (this.employees = data.data));
          },

          createEmployee(){
            this.$Progress.start();
            this.form.post('api/employee')
             .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Employee Created Successfully!'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
            this.loadEmployees();
              Fire.$on('AfterCreate',() => {
               this.loadEmployees();
              });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

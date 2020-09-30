<template>
    <div class="container">
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Time In Rule List</h3>

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
                      <th>Rule Name</th>
                      <th>Time</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="checkIn in checkIns" :key="checkIn.id">
                      <td>{{checkIn.id}}</td>
                      <td>{{checkIn.rule_name}}</td>
                      <td>{{checkIn.checkIn_time}}</td>
                      <td>
                          <a href="http://" @click.prevent="editModal(checkIn)">
                            <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="" @click.prevent="deleteCheckIn(checkIn.id)">
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
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Time In's Info</h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form @submit.prevent="editmode ? updateCheckIn() : createCheckIn()">
        <div class="modal-body">
          <div class="form-group">
              <input v-model="form.rule_name" type="text" name="rule_name"
                  placeholder="Rule Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('rule_name') }">
              <has-error :form="form" field="rule_name"></has-error>
          </div>
          <div class="form-group">
              <input v-model="form.checkIn_time" type="time" name="checkIn_time"
                  placeholder="Time"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('checkIn_time') }">
              <has-error :form="form" field="checkIn_time"></has-error>
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
                checkIns: {},
                form: new Form({
                    id: '',
                    rule_name: '',
                    checkIn_time: ''
                })
            }
        },
        methods: {
          updateCheckIn() {
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/checkIn/'+this.form.id)
            .then(() => {
              // success
              $('#addNew').modal('hide');
               swal.fire(
                    'Updated!',
                    'Time In Rule has been updated successfully!.',
                    'success'
                    )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.start();
            });

          },

          editModal(checkIn) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(checkIn);
          },

          newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },

          deleteCheckIn(id){
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
                    this.form.delete('api/checkIn/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Time In Rule has been deleted successfully.',
                            'success'
                            )
                        Fire.$emit('AfterCreate');
                    }).catch(()=> {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                  }
              })
            },

          loadCheckIns(){
            axios.get('api/checkIn').then(({data}) => (this.checkIns = data.data));
          },

          createCheckIn(){
            this.$Progress.start();
            this.form.post('api/checkIn')
             .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Time In Rule Created Successfully!'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
            this.loadCheckIns();
              Fire.$on('AfterCreate',() => {
               this.loadCheckIns();
              });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

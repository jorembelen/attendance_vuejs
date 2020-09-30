<template>
    <div class="container">
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Time Out Rule List</h3>

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
                    <tr v-for="checkOut in checkOuts" :key="checkOut.id">
                      <td>{{checkOut.id}}</td>
                      <td>{{checkOut.rule_name}}</td>
                      <td>{{checkOut.checkOut_time}}</td>
                      <td>
                          <a href="http://" @click.prevent="editModal(checkOut)">
                            <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="" @click.prevent="deleteCheckOut(checkOut.id)">
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
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Time Out's Info</h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form @submit.prevent="editmode ? updateCheckOut() : createCheckOut()">
        <div class="modal-body">
          <div class="form-group">
              <input v-model="form.rule_name" type="text" name="rule_name"
                  placeholder="Rule Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('rule_name') }">
              <has-error :form="form" field="rule_name"></has-error>
          </div>
          <div class="form-group">
              <input v-model="form.checkOut_time" type="time" name="checkOut_time"
                  placeholder="Time"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('checkOut_time') }">
              <has-error :form="form" field="checkOut_time"></has-error>
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
                checkOuts: {},
                form: new Form({
                    id: '',
                    rule_name: '',
                    checkOut_time: ''
                })
            }
        },
        methods: {
          updateCheckOut() {
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/checkOut/'+this.form.id)
            .then(() => {
              // success
              $('#addNew').modal('hide');
               swal.fire(
                    'Updated!',
                    'Time Out Rule has been updated successfully!.',
                    'success'
                    )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.start();
            });

          },

          editModal(checkOut) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(checkOut);
          },

          newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },

          deleteCheckOut(id){
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
                    this.form.delete('api/checkOut/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Time Out Rule has been deleted successfully.',
                            'success'
                            )
                        Fire.$emit('AfterCreate');
                    }).catch(()=> {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                  }
              })
            },

          loadCheckOuts(){
            axios.get('api/checkOut').then(({data}) => (this.checkOuts = data.data));
          },

          createCheckOut(){
            this.$Progress.start();
            this.form.post('api/checkOut')
             .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Time Out Rule Created Successfully!'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
            this.loadCheckOuts();
              Fire.$on('AfterCreate',() => {
               this.loadCheckOuts();
              });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

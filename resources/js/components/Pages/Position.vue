<template>
    <div class="container">
        
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Positions List</h3>

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
                    <tr v-for="position in positions" :key="position.id">
                      <td>{{position.id}}</td>
                      <td>{{position.pos_name}}</td>
                      <td>
                          <a href="http://" @click.prevent="editModal(position)">
                            <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="" @click.prevent="deletePosition(position.id)">
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
        <h5 v-show="editmode" class="modal-title" id="addNewLabel">Update Position's Info</h5>
        <h5 v-show="!editmode" class="modal-title" id="addNewLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <form @submit.prevent="editmode ? updatePosition() : createPosition()">
        <div class="modal-body">
          <div class="form-group">
              <input v-model="form.pos_name" type="text" name="pos_name"
                  placeholder="Position Name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('pos_name') }">
              <has-error :form="form" field="pos_name"></has-error>
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
                positions: {},
                form: new Form({
                    id: '',
                    pos_name: ''
                })
            }
        },
        methods: {
          updatePosition() {
            this.$Progress.start();
            // console.log('Editing Data');
            this.form.put('api/position/'+this.form.id)
            .then(() => {
              // success
              $('#addNew').modal('hide');
               swal.fire(
                    'Updated!',
                    'Position has been updated successfully!.',
                    'success'
                    )
              this.$Progress.finish();
              Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.start();
            });

          },

          editModal(position) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(position);
          },

          newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },

          deletePosition(id){
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
                    this.form.delete('api/position/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Position has been deleted successfully.',
                            'success'
                            )
                        Fire.$emit('AfterCreate');
                    }).catch(()=> {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                  }
              })
            },

          loadPositions(){
            axios.get('api/position').then(({data}) => (this.positions = data.data));
          },

          createPosition(){
            this.$Progress.start();
            this.form.post('api/position')
             .then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Position Created Successfully!'
                        })
                    this.$Progress.finish();
                })
                .catch(()=>{
                })
            }
        },
        created() {
            this.loadPositions();
              Fire.$on('AfterCreate',() => {
               this.loadPositions();
              });
            // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>

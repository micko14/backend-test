<template>
    <v-container class="grey lighten-5">

    <v-card>
        <v-card-title>
        News Management
        <v-spacer></v-spacer>

              <v-dialog
      v-model="dialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="primary"
          dark
          v-bind="attrs"
          v-on="on"
          small
        >
          Add News
        </v-btn>
      </template>
      <v-card>
        <v-toolbar
          dark
          color="primary"
        >
          <v-btn
            icon
            dark
            @click="dialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              @click="dialog = false"
            >
              Save
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-list
          three-line
          subheader
        >
          <v-subheader>Manage News</v-subheader>
          <v-list-item>
                <form >
                   <span v-if="formTitle == 'Edit News'">
                    <v-text-field
                    
                    v-model="formData.id"
                    label="News ID"
                    readonly
                    required
                    ></v-text-field>

                    </span>

                    <v-text-field
                    v-model="formData.title"
                    label="News Title"
                    required
                    ></v-text-field>


                    <v-text-field
                    v-model="formData.author_id"
                    label="Author ID"
                    required
                    ></v-text-field>


                    <v-text-field
                    v-model="formData.article"
                    label="Article"
                    required
                    ></v-text-field>

                    <v-text-field
                    v-model="formData.is_published"
                    label="Publish Status"
                    required
                    ></v-text-field>


                    <v-btn
                    class="mr-4"
                    @click="saveNews"
                    >
                    Submit
                    </v-btn>
                </form>
          </v-list-item>
        </v-list>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-card>
        <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
        <v-spacer></v-spacer>

        <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
        ></v-text-field>

        </v-card-title>
            <v-data-table
                :headers="headers"
                v-bind:items="newsData"
                :items-per-page="5"
                class="elevation-1"
                :loading="isLoading"
                loading-text="Loading... Please wait"
                :search="search"
            >
                <template v-slot:item="props">
                <tr>
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.title }}</td>
                    <td>{{ props.item.author_id }}</td>
                    <td>{{ props.item.article }}</td>
                    <td>{{ props.item.is_published }}</td>
                    <td>
                      <v-icon
                        small
                        class="mr-2"
                        @click="editItem(props.item)"
                      >
                        mdi-pencil
                      </v-icon>
                      <v-icon
                        small
                        @click="deleteItem(props.item)"
                      >
                        mdi-delete
                      </v-icon>
                    </td>
                </tr>
                </template>
            
            </v-data-table>
    </v-card>
    </v-container>
</template>


<script>

    export default {
        data() {
            return {
                dialogDelete: false,
                editedIndex: -1,
                show1: true,
                formData: {
                  id: '',
                  title: '',
                  author_id: '',
                  article: '',
                  is_published: '',
                },
                search: '',
                dialog: false,
                notifications: false,
                sound: true,
                widgets: false,
                news: {},
                isLoading: true,
                newsData: [],
                headers: [
                    {
                    text: 'ID',
                    align: 'start',
                    sortable: false,
                    value: 'id',
                    },
                    {
                    text: 'Title',
                    align: 'start',
                    value: 'title',
                    },
                    {text: 'Author_id', value: 'author_id'},
                    {text: 'Article' , value: 'article'},
                    {text: 'Status', value: 'is_published'},
                ],
            }
        },

        computed: {
          formTitle () {
            return this.editedIndex === -1 ? 'New News' : 'Edit News'
          },
        },

        watch: {
          dialog (val) {
            val || this.close()
          },
          dialogDelete (val) {
            val || this.closeDelete()
          },
        },

        mounted() {
            axios.get('/api/news')
                .then(response => {
                    this.news = response.data.data;
                    this.newsData = response.data.data;
                    this.isLoading = false;
                });
        },

        methods: {
            saveNews() {
                console.log(this.formData);

                if (this.editedIndex > -1) {
                      axios.put('/api/news/'+this.formData.id, this.formData)
                      .then(response => {
                          this.clearForm();
                          this.alert = true;
                          this.dialog = false;
                          this.getNews();
                      }).catch(error => {
                          // if (error.response.status == 422) {
                          //     this.errors = error.response.data.errors;
                          // }
                          console.log('Error');
                      });
                  } else {
                      axios.post('/api/news', this.formData)
                      .then(response => {
                          this.clearForm();
                          this.alert = true;
                          this.dialog = false;
                          this.getNews();
                      }).catch(error => {
                          // if (error.response.status == 422) {
                          //     this.errors = error.response.data.errors;
                          // }
                          console.log('Error');
                      });
                  }
                  this.close()
            },

            getNews() {
              axios.get('/api/news')
                .then(response => {
                    this.news = response.data.data;
                    this.newsData = response.data.data;
                    this.isLoading = false;
                });
            },

            clearForm(){
              this.formData.title = '';
              this.formData.author_id = '';
              this.formData.article = '';
              this.formData.is_published = '';
            },
            
            editItem (item) {
              this.editedIndex = this.newsData.indexOf(item)
              this.formData = Object.assign({}, item)
              this.dialog = true
            },

            deleteItem (item) {
              this.editedIndex = this.newsData.indexOf(item)
              this.formData = Object.assign({}, item)
              this.dialogDelete = true
            },

            deleteItemConfirm () {
              axios.delete('/api/news/'+this.formData.id)
                      .then(response => {
                          this.alert = true;
                          this.dialog = false;
                          this.getNews();
                      }).catch(error => {
                          // if (error.response.status == 422) {
                          //     this.errors = error.response.data.errors;
                          // }
                          console.log('Error');
                      });
              this.closeDelete()
            },

            close () {
              this.dialog = false
              this.$nextTick(() => {
                this.formData = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
              })
            },

            closeDelete () {
              this.dialogDelete = false
              this.$nextTick(() => {
                this.formData = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
              })
            },
        },

    }
</script>

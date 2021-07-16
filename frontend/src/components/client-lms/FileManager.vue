<template>
  <div class="shadow-con">
    <div class="fm-header">
      <v-icon size="24px" color="black" class="mr-2">far fa-folder-open</v-icon>
      <h3 class="mb-0">File Manager</h3>
    </div>
    <div class="fm-body">
      <div class="row no-gutters mt-3">
        <div class="col-lg-6">
          <v-btn class="my-2 mx-4" color="#f9b418">
            <v-icon left>far fa-file-upload</v-icon> Upload File
          </v-btn>
          <v-btn class="my-2 mx-4" color="#f9b418">
            <v-icon left>far fa-folder</v-icon> Upload Folder
          </v-btn>
          <!-- start of dialog -->
          <v-dialog v-model="dialog" persistent max-width="290">
            <!-- start of dialog button -->
            <template v-slot:activator="{ on }">
              <v-btn
                class="my-2 mx-4"
                dark
                color="blue-grey darken-1"
                v-on="on"
              >
                <v-icon left>far fa-folder-plus</v-icon> New Folder
              </v-btn>
            </template>
            <!-- end of dialog button -->
            <!-- start of dialog popup -->
            <v-card class="pa-2">
              <v-card-title class="headline ma-0 px-2 pb-0 pt-1"
                >New Folder</v-card-title
              >
              <v-text-field
                class="ma-2"
                outlined
                v-model="newfolder"
              ></v-text-field>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn depressed @click="dialog = false">CANCEL</v-btn>
                <v-btn depressed color="#f9b418" @click="dialog = false"
                  >CREATE</v-btn
                >
              </v-card-actions>
            </v-card>
            <!-- end of dialog popup -->
          </v-dialog>
          <!-- end of dialog -->
        </div>
        <div class="col-lg-6 pr-5">
          <v-text-field
            label="Search..."
            prepend-icon="fal fa-search"
          ></v-text-field>
        </div>
      </div>
      <!-- end of function nav -->
      <!-- start of table -->
      <template>
        <v-data-table
          :headers="headers"
          :items="files"
          class="elevation-1 mx-5"
          hide-default-footer
        >
          <template v-slot:item.filename="{ item }">
            <v-icon class="mb-1"> {{ item.icon }} </v-icon>
            {{ item.name }}
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2"> fas fa-download </v-icon>
            <v-icon small @click="deleteItem(item)"> fas fa-trash-alt </v-icon>
          </template>
        </v-data-table>
      </template>
    </div>
  </div>
</template>
<script>
export default {
  name: 'FileManager',
  data() {
    return {
      dialog: false,
      newfolder: 'Untitled Folder',
      headers: [
        {
          text: 'File Name',
          width: '60%',
          align: 'start',
          sortable: false,
          value: 'filename',
        },
        { text: 'File Type', value: 'type' },
        { text: 'Date Modified', value: 'date' },
        { text: 'Actions', value: 'actions', sortable: false, align: 'center' },
      ],
    };
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.files = [
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
        {
          name: 'Layout-V1.psd',
          icon: 'fas fa-file',
          type: 'PSD',
          date: 'March 22, 2020',
        },
      ];
    },

    deleteItem(item) {
      const index = this.files.indexOf(item);
      confirm('Are you sure you want to delete this item?') &&
        this.files.splice(index, 1);
    },
  },
};
</script>

<style lang="stylus">
.shadow-con{
    border-radius: 6px;
    width: 90%;
    margin: auto;
    background-color: white;
    -webkit-box-shadow: -1px -1px 6px -2px rgba(0,0,0,0.65);
    -moz-box-shadow: -1px -1px 6px -2px rgba(0,0,0,0.65);
    box-shadow: -1px -1px 6px -2px rgba(0,0,0,0.65);
    padding-bottom: 25px;
}
.shadow-con th {
      font-size: 16px;
 }
.shadow-con td {
      font-size: 16px;
 }
.fm-header{
    background-color: #f9b418;
    border-radius: 5px 5px 0px 0px;
    padding: 20px 15px;
    display: flex;
    align-items: center;
    font-family: oswald;
}
</style>

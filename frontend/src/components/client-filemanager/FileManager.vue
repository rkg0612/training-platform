<template>
  <v-card class="mt-5 mt-lg-0">
    <v-app-bar
      flat
      class="py-5"
      height="unset"
      color="#f9b418"
      style="font-family: oswald"
    >
      <v-icon size="24px" color="black" class="mr-2">far fa-folder-open</v-icon>
      <h3 class="mb-0">File Manager</h3>
    </v-app-bar>
    <div class="fm-body">
      <div class="row no-gutters mt-3">
        <div class="col-lg-6">
          <!-- start of dialog for upload FOLDER -->
          <v-dialog v-model="fileUpload" persistent max-width="600">
            <!-- start of dialog button -->
            <template v-slot:activator="{ on }">
              <v-btn class="my-2 mx-4" width="130px" color="#f9b418" v-on="on">
                <v-icon left>far fa-file-upload</v-icon> Upload File
              </v-btn>
            </template>
            <!-- end of dialog button -->
            <!-- start of dialog popup -->
            <v-card class="py-2 px-4">
              <v-card-title class="headline mb-2 px-2 pt-1"
                >Upload Folder</v-card-title
              >
              <div
                class="dropzone dropzone-default dropzone-brand dz-clickable"
                id="kt_dropzone_2"
              >
                <div class="dropzone-msg dz-message needsclick">
                  <h3 class="dropzone-msg-title">
                    Drop files here or click to upload.
                  </h3>
                  <span class="dropzone-msg-desc">Upload up to 10 files</span>
                </div>
              </div>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn depressed @click="fileUpload = false">CANCEL</v-btn>
                <v-btn depressed color="#f9b418" @click="fileUpload = false"
                  >UPLOAD</v-btn
                >
              </v-card-actions>
            </v-card>
            <!-- end of dialog popup -->
          </v-dialog>
          <!-- end of dialog for upload FILE -->
          <!-- start of dialog for upload FOLDER -->
          <v-dialog v-model="folderUpload" persistent max-width="600">
            <!-- start of dialog button -->
            <template v-slot:activator="{ on }">
              <v-btn width="130px" class="my-2 mx-4" color="#f9b418" v-on="on">
                <v-icon left>far fa-folder</v-icon> Upload Folder
              </v-btn>
            </template>
            <!-- end of dialog button -->
            <!-- start of dialog popup -->
            <v-card class="py-2 px-4">
              <v-card-title class="headline mb-2 px-2 pt-1"
                >Upload Folder</v-card-title
              >
              <div
                class="dropzone dropzone-default dropzone-brand dz-clickable"
                id="kt_dropzone_2"
              >
                <div class="dropzone-msg dz-message needsclick">
                  <h3 class="dropzone-msg-title">
                    Drop files here or click to upload.
                  </h3>
                  <span class="dropzone-msg-desc">Upload up to 10 folder</span>
                </div>
              </div>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn depressed @click="folderUpload = false">CANCEL</v-btn>
                <v-btn depressed color="#f9b418" @click="folderUpload = false"
                  >UPLOAD</v-btn
                >
              </v-card-actions>
            </v-card>
            <!-- end of dialog popup -->
          </v-dialog>
          <!-- end of dialog for upload FOLDER -->
          <!-- start of dialog for new folder -->
          <v-dialog v-model="folder" persistent max-width="350">
            <!-- start of dialog button -->
            <template v-slot:activator="{ on }">
              <v-btn
                width="130px"
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
                <v-btn depressed @click="folder = false">CANCEL</v-btn>
                <v-btn depressed color="#f9b418" @click="folder = false"
                  >CREATE</v-btn
                >
              </v-card-actions>
            </v-card>
            <!-- end of dialog popup -->
          </v-dialog>
          <!-- end of dialog for new folder -->
        </div>
        <div class="col-lg-6 px-5">
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
    <br />
  </v-card>
</template>
<script>
export default {
  name: 'FileManager',
  data() {
    return {
      folder: false,
      folderUpload: false,
      fileUpload: false,
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
        {
          text: 'Actions',
          value: 'actions',
          sortable: false,
          align: 'center',
        },
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

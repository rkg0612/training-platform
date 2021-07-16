<template>
  <v-card color="grey lighten-4" flat class="pa-4 text-left mt-3">
    <v-card>
      <component-loader :active="loading.newNote"></component-loader>
      <v-card-title>
        <span class="ml-1">Notes:</span>
      </v-card-title>
      <div style="max-width: 95%" class="mx-auto mb-10">
        <validation-observer ref="newNoteObs">
          <validation-provider
            rules="required"
            name="value"
            v-slot="{ errors }"
          >
            <quill-editor
              v-model="newNote"
              :options="editorOption"
              class="editor"
            >
              <div class="output ql-bubble">
                <div class="ql-editor" v-html="content"></div>
              </div>
            </quill-editor>
            <span class="kt-font-danger validate-error" v-if="errors">
              {{ errors[0] }}
            </span>
          </validation-provider>
        </validation-observer>
      </div>
      <v-card-actions>
        <div class="text-right ml-3 mb-3 mt-3">
          <v-btn dark color="light-blue darken-4" @click="handlePosting()">
            Post
          </v-btn>
        </div>
      </v-card-actions>
    </v-card>
    <v-card
      color="white"
      class="mt-3"
      v-for="note in notes"
      v-bind:key="note.id"
    >
      <component-loader :active="loading.fetchNotes"></component-loader>
      <v-card-title class="headline">
        {{ note.author.name }}
      </v-card-title>
      <v-card-subtitle>
        {{ note.updated_at | displayDateTime }}
      </v-card-subtitle>
      <v-card-text
        class="text--primary"
        v-html="note.value"
        v-show="!editMode || note.id !== selectedNote.id"
      >
      </v-card-text>
      <div style="max-width: 95%" class="mx-auto">
        <quill-editor
          v-show="editMode && note.id === selectedNote.id"
          :content="editorContent"
          :options="editorOption"
          @change="onEditorChange($event)"
        >
        </quill-editor>
        <span
          class="kt-font-danger validate-error"
          v-if="editorContent === null"
        >
          The notes field is required.
        </span>
      </div>
      <v-card-actions v-show="userId === note.author.id">
        <v-btn
          color="blue-grey 1"
          dark
          text
          @click="editNote(note)"
          v-show="!editMode"
        >
          <v-icon left>fal fa-pencil</v-icon>
          Edit
        </v-btn>
        <v-btn
          color="blue-grey 1"
          dark
          text
          @click="handleUpdate"
          v-show="editMode"
          :loading="loading.updateNote"
        >
          <v-icon left>fal fa-save</v-icon>
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
    <div class="row">
      <div class="col">
        <v-btn text disabled>
          Showing {{ currentNumberOfNotes }} out of
          {{ totalNumberOfNotes }}
        </v-btn>
      </div>
      <div class="col">
        <v-btn
          color="blue"
          dark
          text
          :loading="loading.fetchNotes"
          @click="loadMore"
          :disabled="lastPage === page"
        >
          Load more...
        </v-btn>
      </div>
    </div>
  </v-card>
</template>

<script>
import { quillEditor } from 'vue-quill-editor';
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import { isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'Notes',
  props: ['unitId'],
  components: {
    quillEditor,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      editorOption: {
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }, { background: [] }],
            [{ font: [] }],
            [{ align: [] }],
          ],
        },
      },
      content: '',
      selectedNote: {
        id: '',
        value: '',
        author_id: '',
      },
      editorContent: '',
      newNote: '',
      totalNotes: '',
      currentNumberOfNotes: 5,
      totalNumberOfNotes: 5,
      loading: {
        newNote: false,
        updateNote: false,
        fetchNote: false,
      },
      page: 1,
      lastPage: 10,
      editMode: false,
    };
  },
  computed: {
    notes() {
      return this.$store.state.notes.items;
    },
    userId() {
      return this.$auth.user().id;
    },
  },
  created() {
    this.fetchNotes();
    this.$validator.localize('en', {
      attributes: {
        value: 'notes',
      },
    });
  },
  methods: {
    fetchNotes() {
      this.$store
        .dispatch('notes/getNotes', {
          unitId: this.$route.params.id,
          page: this.page,
        })
        .then(({ data }) => {
          this.$store.commit('notes/setNotes', data.notes.data);
          this.currentNumberOfNotes = data.notes.to;
          this.totalNumberOfNotes = data.notes.total;
          this.lastPage = data.notes.last_page;
        });
    },
    loadMore() {
      this.page++;
      this.loading.fetchNotes = true;
      this.$store
        .dispatch('notes/getNotes', {
          unitId: this.unitId,
          page: this.page,
        })
        .then(({ data }) => {
          const notes = data.notes.data;
          this.currentNumberOfNotes = data.notes.to;
          this.loading.fetchNotes = false;
          notes.forEach((item) => {
            this.$store.commit('notes/addNotes', item);
          });
        });
    },
    editNote(item) {
      this.editMode = true;
      this.selectedNote = item;
      this.editorContent = this.selectedNote.value;
    },
    updateNote() {
      this.editMode = false;
      this.loading.updateNote = true;
      this.$store
        .dispatch('notes/editNote', this.selectedNote)
        .then(({ data }) => {
          this.$store.commit('notes/updateNote', data);

          this.$notify('success', 'Notes updated!', true);
        })
        .finally(() => {
          this.loading.updateNote = false;
        });
    },
    onEditorChange({ html }) {
      this.selectedNote.value = html;
    },
    handlePosting() {
      const isValid = this.$refs.newNoteObs.validate();
      isValid.then((result) => {
        if (result) {
          this.saveNote();
        }
      });
    },
    handleUpdate() {
      if (!_isEmpty(this.selectedNote.value)) {
        return this.updateNote();
      }
      return this.$notify('error', 'The note field is required.', true);
    },
    saveNote() {
      this.loading.newNote = true;
      this.$store
        .dispatch('notes/storeNote', {
          unitId: this.unitId,
          value: this.newNote,
        })
        .then(({ data }) => {
          this.$store.commit('notes/addNote', data.note);
          this.currentNumberOfNotes++;
          this.totalNumberOfNotes++;
          this.newNote = null;
          this.$notify('success', 'Notes added!', true);
        })
        .finally(() => {
          setTimeout(() => {
            this.loading.newNote = false;
          }, 500);
        });
    },
  },
};
</script>

<style scoped></style>

<template>
  <div>
    <div class="row">
      <div class="col-lg-8">
        <v-card>
          <component-loader :active="isFetching"></component-loader>
          <!-- start of title -->
          <v-app-bar flat class="py-3" height="unset">
            <div>
              <h3>
                <b>{{ module.name }}:</b>
                {{ unit.name }}
              </h3>
              <h4 class="m-0">
                Status: {{ isCompleted ? 'Completed' : 'Open' }}
              </h4>
            </div>
          </v-app-bar>
          <!-- start of title -->

          <div class="body-box text-right mt-4">
            <div class="video-wrapper text-center mt-3">
              <div class="mx-auto" id="unit-content" v-html="contents"></div>
              <div class="text-center mt-3 mod-tag">
                <span>Tags:</span>
                <v-chip
                  class="ml-1 grey lighten-3 mt-2"
                  small
                  v-for="tag in tags"
                  v-bind:key="tag.id"
                  >{{ tag.name }}</v-chip
                >
              </div>
              <div class="text-center mt-3 w-75 p-3 description">
                <p>{{ unit.description }}</p>
              </div>
              <v-menu open-on-hover top offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    text
                    small
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon right dark class="m-1">fal fa-download</v-icon>
                    Downloadable Files
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                    v-for="(item, index) in downloadLinks"
                    :key="index"
                    :href="item.link"
                    target="_blank"
                  >
                    <v-list-item-title>
                      {{ item.title }}
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
              <v-menu>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn v-bind="attrs" v-on="on" text small>
                    <v-icon class="mr-2">fal fa-list-ul</v-icon>
                    More Options
                  </v-btn>
                </template>

                <v-list>
                  <v-list-item>
                    <v-btn
                      dark
                      small
                      outlined
                      color="grey darken-3"
                      @click.stop="showShareUnit = true"
                      text
                    >
                      <v-icon class="mr-2">fal fa-share-alt</v-icon>
                      Share
                    </v-btn>
                    <share-unit :unit_id="unit.id" v-model="showShareUnit" />
                  </v-list-item>
                  <v-list-item>
                    <v-btn
                      dark
                      small
                      outlined
                      color="grey darken-3"
                      @click.stop="showAssignUnit = true"
                      text
                    >
                      <v-icon class="mr-2">fal fa-users-cog</v-icon>
                      Assign
                    </v-btn>
                    <assign-unit :unit_id="unit.id" v-model="showAssignUnit" />
                  </v-list-item>
                  <v-list-item>
                    <v-btn
                      dark
                      small
                      outlined
                      color="grey darken-3"
                      @click.stop="showUnitPlaylist = true"
                      text
                    >
                      <v-icon class="mr-2">fal fa-layer-plus</v-icon>
                      Add to Playlist
                    </v-btn>
                    <unit-playlist :unit="unit" v-model="showUnitPlaylist" />
                  </v-list-item>
                </v-list>
              </v-menu>
              <v-card
                color="grey lighten-4"
                flat
                class="pa-4 text-left mt-3"
                v-if="quiz !== null && quizResult == null"
              >
                <v-card-title>Quiz</v-card-title>
                <v-card class="px-5 py-4">
                  <v-row
                    v-for="(question, index) in quiz.questions"
                    v-bind:key="question.id"
                  >
                    <v-col>
                      <h4>{{ question.value }}</h4>
                      <div
                        v-for="answer in question.answers"
                        v-bind:key="answer.id"
                      >
                        <div class="form-check mb-2">
                          <input
                            class="form-check-input"
                            :type="question.is_multiple ? 'checkbox' : 'radio'"
                            :name="index"
                            :value="answer.id"
                            @click="selectAnswer(answer.id, question.id)"
                            :id="answer.id"
                          />
                          <label class="form-check-label" :for="answer.id">{{
                            answer.value
                          }}</label>
                        </div>
                      </div>
                    </v-col>
                  </v-row>
                </v-card>
              </v-card>
              <v-card
                color="grey lighten-4"
                flat
                class="pa-4 text-left mt-3"
                v-else-if="quizResult"
              >
                <v-card-title>Quiz</v-card-title>
                <v-card class="px-5 py-4">
                  <v-row
                    v-for="(question, index) in quizResultForm"
                    v-bind:key="question.id"
                  >
                    <v-col>
                      <h4>{{ question.value }}</h4>
                      <div
                        v-for="(answer, index) in question.answers"
                        v-bind:key="answer.id"
                      >
                        <div class="form-check m-2">
                          <input
                            class="form-check-input"
                            :type="question.is_multiple ? 'checkbox' : 'radio'"
                            :name="index"
                            :checked="
                              isUserAnswer(question.user_answer, answer.id)
                            "
                            :value="answer.id"
                            disabled
                            @click="selectAnswer(answer.id, question.id)"
                            :id="answer.id"
                          />
                          <label
                            class="form-check-label"
                            :for="answer.id"
                            :class="
                              answer.is_correct ? 'text-success' : 'text-danger'
                            "
                            >{{ answer.value }}</label
                          >
                        </div>
                      </div>
                    </v-col>
                  </v-row>
                  <v-row
                    >TOTAL SCORE: {{ quizResult.total_score }} /
                    {{ quizResult.total_number_of_questions }}</v-row
                  >
                </v-card>
              </v-card>
              <h4 class="text-center mt-6 subtitle-2">
                Click the button below once you're done.
              </h4>
              <v-btn
                text
                class="mr-3"
                color="blue-grey lighten-3"
                @click="previous"
              >
                <v-icon>fal fa-backward</v-icon>
              </v-btn>
              <v-btn
                v-if="quiz === null"
                color="primary"
                :disabled="isCompleted"
                :loading="loadingMark"
                @click="markAsCompleted"
                text
                >Mark as Completed</v-btn
              >
              <v-btn
                text
                v-else
                color="primary"
                :disabled="quizResult !== null && quizResultForm !== null"
                :loading="loadingMark"
                @click="submitQuiz"
                >Submit</v-btn
              >
              <v-btn
                text
                class="ml-3"
                color="blue-grey lighten-3"
                @click="next"
              >
                <v-icon>fal fa-forward</v-icon>
              </v-btn>
              <notes :unitId="$route.params.id"></notes>
            </div>
          </div>
        </v-card>
      </div>
      <!-- end of class="col-lg-8" -->
      <div class="col-lg-4">
        <!-- start of progress bar -->
        <v-card>
          <v-app-bar flat>
            <h3 class="m-0">Module Progress</h3>
          </v-app-bar>
          <div class="mx-3 py-4">
            <v-progress-linear
              color="#f9b418"
              height="30"
              :value="module.build.progress"
              striped
              rounded
            >
              <template v-slot="{ value }">
                <strong>{{ Math.ceil(module.build.progress) }}%</strong>
              </template>
            </v-progress-linear>
            <p class="subtitle-2">
              This progress bar updates whenever you complete a unit in this
              module.
            </p>
          </div>
        </v-card>
        <!-- end of progress bar -->
        <br />
        <!-- start of related units -->
        <related-units :unitId="$route.params.id"></related-units>
        <!-- end of related units -->
      </div>
    </div>
  </div>
</template>
<script>
import ShareUnit from './dialog-box/ShareUnit.vue';
import AssignUnit from './dialog-box/AssignUnit.vue';
import UnitPlaylist from './dialog-box/UnitPlaylist.vue';
import RelatedUnits from './UnitPage/RelatedUnits';
import Notes from './UnitPage/Notes';
import { findIndex as _findIndex, isEmpty as _isEmpty } from 'lodash-es';

export default {
  name: 'UnitPage',

  components: {
    Notes,
    RelatedUnits,
    ShareUnit,
    AssignUnit,
    UnitPlaylist,
  },

  data: () => ({
    loadingMark: false,
    isFetching: false,
    showShareUnit: false,
    showAssignUnit: false,
    showUnitPlaylist: false,
    quizDialog: false,
    modprog: 78,
    answers: [],
    player: null,
    vPlayer: null,
    played: false,
    quizAnswered: false,
    downloadLinks: [
      {
        title: 'Salesbook',
        link:
          'https://docs.google.com/document/d/1yXDXBMDhBqxUrh_n2AWlAUtIYPOSFCCbPgtn74jOdsw/edit?usp=sharing',
      },
      {
        title: 'Call Guides',
        link:
          'https://docs.google.com/presentation/d/1HEhwl-N4R8zf1gNiNhV_xxOo5uCj2qlM0R8b7tG-2fk/edit?usp=sharing',
      },
      {
        title: 'File Manager',
        link: '/clients/file-manager',
      },
    ],
  }),

  computed: {
    unit() {
      return this.$store.state.clientUnit.selectedItem.unit;
    },
    tags() {
      return this.$store.state.clientUnit.selectedItem.unit.tags;
    },
    module() {
      return this.$store.state.clientUnit.selectedItem.unit.module;
    },
    contents() {
      return this.$store.state.clientUnit.selectedItem.unit.content;
    },
    quiz() {
      return this.$store.state.clientUnit.selectedItem.unit.quiz;
    },
    quizResult() {
      return this.$store.state.clientUnit.quizResult;
    },
    quizResultForm() {
      return this.quizResult ? JSON.parse(this.quizResult.quiz_form) : null;
    },
    userId() {
      return this.$auth.user().id;
    },
    isCompleted() {
      if (this.$store.state.relatedUnits.type === 'playlist') {
        const index = _findIndex(
          this.$store.state.relatedUnits.playlist.units,
          {
            id: this.unit.id,
          }
        );
        return this.$store.state.relatedUnits.playlist.units[index]
          .is_completed;
      }
      return (
        this.$store.state.clientUnit.selectedItem.is_completed === 1 ||
        this.$store.state.clientUnit.selectedItem.is_completed === true
      );
    },
  },

  created() {
    // this.maybeTrackSeachAction();
    this.fetchUnit();
    this.isPlaylistOrSingleUnit();
  },

  // updated() {
  //   this.loadVimeoPlayerSettings(
  //     this.$store.state.markAsCompleted.current_unit.id
  //   );
  // },

  methods: {
    maybeTrackSeachAction() {
      if (this.$route.query.track && this.$route.query.track == 1) {
        console.log('this.$route.query.track');
        const modelId = this.$route.params.id;
        const modelType = 'App\\Models\\Unit';
        console.log(modelType);
        this.$http.post('search-analytics', {
          model_type: modelType,
          model_id: modelId,
        });
      }
    },
    isQuizAlreadyAnswered() {
      this.axios
        .get(`/client/lms/quiz/${this.quiz.id}`, {
          params: {
            unit_id: this.unit.id,
          },
        })
        .then(({ data }) => {
          this.$store.commit('clientUnit/setQuizResult', data.data);
        })
        .catch((error) => {
          this.$store.commit('clientUnit/setQuizResult', null);
        });
    },

    selectAnswer(answerId, questionId) {
      const answer = {
        questionId: questionId,
        answerId: answerId,
      };

      const existingIndex = _findIndex(this.answers, answer);

      if (existingIndex === -1) {
        return this.answers.push(answer);
      }

      return this.answers.splice(existingIndex, 1);
    },

    isUserAnswer(userAnswersId, currentAnswerId) {
      return userAnswersId.includes(currentAnswerId);
    },

    submitQuiz() {
      if (_isEmpty(this.answers)) {
        return this.$swal({
          title: 'Unit Completion Failed!',
          text: 'Please answer the quiz first!',
          icon: 'warning',
        });
      }
      this.markAsCompleted();
      this.loadingMark = true;
      this.axios
        .post('client/lms/quiz', {
          answers: this.answers,
          unit_id: this.$store.state.markAsCompleted.current_unit.id,
        })
        .then(({ data }) => {
          this.quizAnswered = true;
          this.isQuizAlreadyAnswered();
          this.markAsCompleted();
        })
        .finally(() => {
          this.loadingMark = false;
          this.quizAnswered = true;
        });
    },

    isPlaylistOrSingleUnit() {
      const { playlistId } = this.$route.params;

      if (playlistId == undefined) {
        return this.determinePlaylistType(playlistId);
      }

      if (
        this.$store.state.markAsCompleted.playlist_id ||
        (Array.isArray(this.$store.state.markAsCompleted.type) &&
          this.$store.state.markAsCompleted.playlist_id)
      ) {
        this.loadingMark = true;
        return this.determinePlaylistType(playlistId);
      }
      if (!this.$store.state.markAsCompleted.current_unit.id) {
        return this.$store.commit(
          'markAsCompleted/setCurrentUnitId',
          this.$route.params.id
        );
      }
    },

    determinePlaylistType(playlistId = null) {
      if (playlistId === null || playlistId === undefined) {
        return;
      }
      return this.$store
        .dispatch('markAsCompleted/determinePlaylistType', {
          playlistId: !_isEmpty(this.$store.state.markAsCompleted.playlist_id)
            ? this.$store.state.markAsCompleted.playlist_id
            : playlistId,
          type: this.$store.state.markAsCompleted.type,
          unitId: this.$store.state.markAsCompleted.current_unit.id,
        })
        .then(({ data }) => {
          this.loadingMark = false;
          this.$store.commit('markAsCompleted/setType', data);
          this.$store.commit('relatedUnits/setType', 'playlist');
        });
    },

    markAsCompleted() {
      this.loadingMark = true;
      this.$store
        .dispatch('markAsCompleted/markAsCompleted', {
          playlistId: this.$route.params.playlistId,
          type: this.$store.state.relatedUnits.type,
          unitId: this.$store.state.markAsCompleted.current_unit.id,
        })
        .then(({ data }) => {
          this.$store.commit(
            'relatedUnits/setIsCompletedToTrue',
            this.$store.state.markAsCompleted.current_unit.id
          );
          this.$store.commit('markAsCompleted/setIsCompleted', true);
          this.$store.commit('clientUnit/setCompleted', true);
          this.$notify('success', 'Unit Mark as completed!');
          this.loadingMark = false;
        });
    },
    loadVimeoPlayerSettings(unit_id = null) {
      console.log('load');
      let player = document.querySelector('#unit-content > iframe');
      let play = false;
      let vm = this;
      this.vPlayer = new Vimeo.Player(player);
      this.vPlayer.on('play', function () {
        vm.processPlayedVideo(unit_id);
      });
    },
    processPlayedVideo(unit_id) {
      let type = this.$store.state.relatedUnits.type;

      if (!this.played) {
        this.$http
          .post('/mark-user-unit-video-played', {
            unit_id: unit_id,
            type: type,
            playlistId: this.$route.params.playlistId,
          })
          .then((response) => {
            this.played = true;
            parent.focus();
            this.$notify('success', 'Added to your completed videos.');
            console.log('played');
          });
      } else {
        console.log('already played');
      }
    },
    fetchUnit() {
      this.isFetching = true;
      this.$store
        .dispatch('clientUnit/getClientUnit', this.$route.params.id)
        .then(({ data }) => {
          this.$store.commit('markAsCompleted/setCurrentUnitId', data.unit.id);
          this.$store.commit('clientUnit/setSelectedItem', data);
          this.isFetching = false;
          this.$store.commit(
            'markAsCompleted/setIsCompleted',
            data.is_completed ? true : false
          );
          this.played = data.is_played;
          if (data.unit.quiz_id) {
            this.isQuizAlreadyAnswered();
          } else {
            this.$store.commit('clientUnit/setQuizResult', null);
          }
        })
        .catch(() => {
          this.$router.push({
            name: 'PageNotFound',
          });
        })
        .finally(() => {
          this.loadVimeoPlayerSettings(
            this.$store.state.markAsCompleted.current_unit.id
          );
        });
    },
    addToFavoriteDialog() {
      this.$swal({
        text: 'Successfully added to Favorite list.',
        icon: 'success',
      });
    },
    next() {
      if (this.$route.params.playlistId) {
        return this.navigateAroundPlaylist('next');
      } else {
        return this.$http
          .post(`client/lms/related-units/`, {
            unitId: this.$route.params.id,
            type: 'single',
            action: 'next',
          })
          .then(({ data }) => {
            if (data !== null) {
              return this.$router.push({
                name: 'UnitPage',
                params: {
                  id: data,
                },
              });
            }

            return this.$notify(
              'warning',
              'This is the last unit in the list.'
            );
          });
      }
    },
    previous() {
      const type = this.$store.state.relatedUnits.type;
      if (type === 'playlist') {
        return this.navigateAroundPlaylist('back');
      } else {
        return this.$http
          .post(`client/lms/related-units/`, {
            unitId: this.$route.params.id,
            type: 'single',
            action: 'prev',
          })
          .then(({ data }) => {
            if (data !== null) {
              return this.$router.push({
                name: 'UnitPage',
                params: {
                  id: data,
                },
              });
            }

            return this.$notify(
              'warning',
              'This is the first unit in the list.'
            );
          });
      }
    },
    navigateAroundPlaylist(type) {
      if (type === 'back') {
        const prevId = this.$store.getters[
          'relatedUnits/getPrevUnitInPlaylist'
        ](this.$route.params.id);

        if (Number.isInteger(prevId)) {
          return this.$router.push({
            name: 'UnitPage',
            params: {
              id: prevId,
              playlistId: this.$route.params.playlistId,
            },
          });
        }

        return this.$notify('warning', 'This is the first unit in the list.');
      }
      if (type === 'next') {
        const nextId = this.$store.getters[
          'relatedUnits/getNextUnitInPlaylist'
        ](this.$route.params.id);

        if (Number.isInteger(nextId)) {
          return this.$router.push({
            name: 'UnitPage',
            params: {
              id: nextId,
              playlistId: this.$route.params.playlistId,
            },
          });
        }

        return this.$notify('warning', 'This is the last unit in the list.');
      }
    },
  },
};
</script>

<style lang="stylus" scoped>
.editor, .output
  height 10rem

.description
  margin auto
</style>

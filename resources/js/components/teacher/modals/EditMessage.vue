<template>
  <transition name="modal">
      <div class="overlay">
          <div class="editMessage">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <slot name="header"></slot>
                      </div>
                      <div class="modal-body">
                          <div class="form-group">
                              <label id="label-message"><strong><p class="message-paragraph">Message:</p></strong>
                                  <textarea id="message" name="message" v-model="message" rows="5" cols="35"></textarea>
                              </label>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <slot name="footer"></slot>
                          <button type="button" id="update-message" class="btn btn-primary" @click.once="updateMessage()">
                              Update
                          </button>
                      </div>
                      <div class="flash-wrapper">
                          <div v-if="this.confirm === true" :style="flashStyleInfo.show" class="flex flash-container">
                             <p>{{ flashTextInfo }}</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </transition>
</template>

<script>
export default {
  props: ['message_text', 'message_id'],
  data() {
      return {
          message: this.message_text,
          confirm: false,
          flashTextInfo: '',
          flashStyleInfo: {
              'display': 'none',
              show: {
                  'display': 'block',
                  'position': 'relative',
                  'top': '2px',
                  'left': '22px',
                  'background-color': 'rgba(60, 204, 102, 0.3)',
                  'width': '333px',
                  'height': '35px',
                  'text-align': 'center',
                  'border-radius': '7px',
              }
          },
      }
  },
  methods: {
      updateMessage() {
          let _this = this;
          var id = this.message_id;
          axios.put('update-message/' + id, {
              id: id,
              message: _this.message,
          }).then(function (response) {
              let updateMessageBtn = document.getElementById("update-message");
              updateMessageBtn.addEventListener('click', function () {
                  _this.flashTextInfo = response.data.message;
                  _this.confirm = true;
              }.bind(this), false);
          }).catch(function (error) {
              console.log(error.response.data)
          });
      },
  },
  mounted() {
      this.updateMessage()
  },
}
</script>

<style scoped>

    .overlay {
        position: absolute;
        top: 200px;
        width: 52%;
        height: 75%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
    .editMessage {
        display: table;
        position: relative;
        top: 150px;
        left: 33%;
        width: 400px;
        height: 300px;
        background-color: #4c6fb1;
        z-index: 9999;
        padding: 10px 10px 10px 10px;
        transition: opacity .3s ease;
    }
    .flash-wrapper {
        margin-bottom: 15px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 5px;
    }

</style>

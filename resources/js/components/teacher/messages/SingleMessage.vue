<template>
  <div class="container">
      <div class="container col-md-10 mt-5">
          <div class="card-body"><h5><strong class="header-text">My Single Message</strong></h5></div>
          <div class="row text-center">
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="mb-2">
                     <strong>Id:</strong>
                  </div>
                  <div class="form-group bg-light pt-2 pb-2">
                     <strong>{{ message.id }}</strong>
                  </div>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="mb-2">
                    <strong>Message:</strong>
                  </div>
                  <div class="form-group bg-light pt-2 pb-2">
                     <p class="mt-3">{{ message.message }}</p>
                  </div>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="mb-2">
                     <strong>Create Date:</strong>
                  </div>
                  <div class="form-group bg-light pt-2 pb-2">
                     <p class="mt-3">{{ message.created_at | formatDate(message.created_at) }}</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>
<script>
export default {
    data() {
        return {
            message: {},
        }
    },

  methods: {
      getMessage() {
          let id = window.location.href.split('/').pop();
          axios.get('/teacher/single-message/' + id).then(response => {
              this.message = response.data.message
              console.log(this.message)
          });
      },
  },
  filters: {
      formatDate(value) {
          var date = new Date(value);
          let minutesFormat = '';
          if (date.getMinutes() < 10) {
              minutesFormat += "0" + date.getMinutes();
          } else {
              minutesFormat += date.getMinutes();
          }
          let timestamps = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getHours();
          return timestamps += " " + date.getHours() + ":" + minutesFormat
      }
  },
  mounted() {
     this.getMessage()
  },
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }

</style>

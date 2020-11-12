<template>
  <div class="container">
     <div class="row justify-content-center">
        <div class="col-md-12 mt-2 mb-4">
           <h5 v-if="errors" :style="{ display: 'none' }" class="text-center">
              <strong>Class Name:
                 <p class="d-inline text-danger">{{ class_name }}</p>
              </strong>
           </h5>
          <div v-if="errors" :style="{ display: 'none' }" class="card-body"><h5><strong class="header-text">My Grades</strong></h5></div>
        </div>
           <div :style="switchFlashStyle" class="flex flash-container">
              <div v-for="error in errors" class="error-explode">
                  <p>{{ error }}</p>
              </div>
           </div>
           <div v-for="semester in semesters" class="sem-1 bg-light">
              <h6 v-if="semester.name === '1'" :style="{ display: 'block' }" class="text-center pt-5">Semester 1</h6>
              <div v-if="semester.name == '1'" :style="{ display: 'block' }" class="teacher_name">
                 <div class="col-md-12">
                    <p class="text-primary text-center"><b>{{ teacher + ' - Teacher' }}</b></p>
                 </div>
              </div>
              <table v-if="semester.name === '1'" :style="{ display: 'block' }" class="table table-bordered">
                 <thead>
                    <tr>
                      <th class="text-center yellow-tr">Subject</th>
                      <th class="text-center green-tr">Rating</th>
                      <th class="text-center blue-tr">Average</th>
                      <th class="text-center">Date</th>
                    </tr>
                 </thead>
                 <tbody>
                    <tr v-for="(my_grade, index) in my_grades.data" :key="my_grade.id">
                      <td class="text-center text-danger yellow-td" width="45%">{{ subjects }}</td>
                      <td class="text-center green-td" width="14%">{{ my_grade }}</td>
                      <td class="text-center blue-td" width="14%">{{ avg }}</td>
                      <td class="text-center" width="27%">{{ date[index] }}</td>
                    </tr>
                </tbody>
             </table>
              <div v-if="semester.name == '1'" :style="{ display: 'block' }" class="pag-container pl-2">
                <pagination :data="my_grades" @pagination-change-page="getMyGrades"></pagination>
              </div>
         </div>
        <div v-for="semester in semesters" class="sem-2 bg-light">
           <h6 v-if="semester.name == '2'" :style="{ display: 'block' }" class="text-center pt-5">Semester 2</h6>
           <div v-if="semester.name == '2'" :style="{ display: 'block' }" class="teacher_name">
              <div class="col-md-12">
                 <p class="text-primary text-center"><b>{{ teacher + ' - Teacher' }}</b></p>
              </div>
           </div>
           <table v-if="semester.name == '2'" :style="{ display: 'block' }" class="table table-bordered">
              <thead>
                 <tr>
                    <th class="text-center yellow-tr">Subject</th>
                    <th class="text-center green-tr">Rating</th>
                    <th class="text-center blue-tr">Average</th>
                    <th class="text-center">Date</th>
                 </tr>
              </thead>
              <tbody>
                 <tr v-for="(my_grade, index) in my_grades.data" :key="my_grade.id">
                    <td class="text-center text-danger yellow-td" width="45%">{{ subjects }}</td>
                    <td class="text-center green-td" width="15%">{{ my_grade.rating }}</td>
                    <td class="text-center blue-td" width="15%">{{ avg }}</td>
                    <td class="text-center" width="25%">{{ date[index] }}</td>
                 </tr>
             </tbody>
           </table>
           <div v-if="semester.name == '2'" :style="{ display: 'block' }" class="pag-container pl-2">
              <pagination :data="my_grades" @pagination-change-page="getMyGrades"></pagination>
           </div>
        </div>
     </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            semesters: {},
            class_name: {},
            avg: {},
            subjects: {},
            teacher: {},
            my_grades: {},
            date: {},
            switchFlashStyle: '',
            errors: [],
            flashStyle: {
               'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '100px',
                    'left': '0%',
                    'background-color': 'rgba(245, 34, 70, 0.3)',
                    'width': '350px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                }
            },
        }
    },
    methods: {
        getMyGrades(page) {
            let self = this;
            if (typeof page === 'undefined') {
                 page = 1;
            }
            let id = window.location.href.split('/').pop();
            axios.get('/pupil/my-grades/' + id + '?page=' + page).then(response => {
                this.semesters = response.data.semesters
                this.class_name = response.data.class_name
                this.teacher = response.data.teacher
                this.avg = response.data.avg
                this.subjects = response.data.subjects
                this.my_grades = response.data.my_grades
                this.date = response.data.date
                self.errors.push(response.data.message);
                for (let i = 0; i < self.errors.length; i++) {
                    if (self.errors[i] !== undefined) {
                      this.switchFlashStyle = this.flashStyle.show;
                    } else {
                      this.switchFlashStyle = this.flashStyle
                    }
                }
            })
        },
    },
    mounted() {
        this.getMyGrades()

    }
}
</script>

<style scoped>

   .table {
       display: none;
   }
   .table > tr {
       width: 100%;
   }
   .yellow-tr {
       background-color:rgba(248, 230, 117, 0.7);
   }
   .yellow-td {
       background-color:rgba(248, 230, 117, 0.5);
   }
   .green-tr {
       background-color:rgba(60, 179, 78, 0.7);
   }
   .green-td {
      background-color:rgba(60, 179, 78, 0.5);
   }
   .blue-tr {
       background-color:rgba(175, 225, 240, 0.7);
   }
   .blue-td {
      background-color:rgba(175, 225, 240, 0.5);
   }
   .sem-1 {
      display: block;
      width: 50%;
      height: auto;
      float: left;
      border: 1px solid #efeff2;
   }
   .sem-2 {
      display: block;
      width: 50%;
      height: auto;
      float: right;
      border: 1px solid #efeff2;
   }
   .header-text {
      color: #8f8f8f;
   }
   .pag-container {
      display: none;
   }
   .flash-container {
      display: none;
   }
   .flash-container p {
      position: relative;
      top: 4px;
   }
   .teacher_name {
      display: none;
   }
   .error-explode p {
      padding-top: 2px;
   }

</style>

<template>
   <div class="container">
       <div class="row justify-content-center mt-4">
           <div :style="switchFlashStyle" class="flex flash-container">
               <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                   <p>{{ alert }}</p>
               </div>
           </div>
           <div v-if="alerts[0] === undefined" :style="{ display: 'block' }" class="col-md-12 mt-2 mb-4">
               <h5 class="text-center">
                   <strong>Class Name:
                       <p class="d-inline text-danger">{{ className }}</p>
                   </strong>
               </h5>
           </div>
           <div class="card-body"><h5><strong class="header-text">My Grades</strong></h5></div>
           <div class="grades-container">
               <div v-for="semester in semester1" class="sem-1 bg-light mb-5">
                   <h6 v-if="semester == '1'" class="text-center pt-5">Semester 1</h6>
                   <div v-if="semester == '1'" class="teacher_name">
                       <div class="col">
                          <p class="text-primary text-center"><b>{{ teacher + ' - Teacher' }}</b></p>
                       </div>
                   </div>
                   <div class="table-container1">
                       <table v-if="semester == '1'" class="table table-bordered">
                           <thead>
                               <tr>
                                  <th class="text-center yellow-tr">Subject</th>
                                  <th class="text-center green-tr">Rating</th>
                                  <th class="text-center blue-tr">Average</th>
                                  <th class="text-center">Date</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr v-for="(myGrade, index) in myGradesSem1">
                                  <td class="text-center text-danger yellow-td" width="40%">{{ subjects[0].name }}</td>
                                  <td class="text-center green-td" width="20%">{{ myGrade.rating }}</td>
                                  <td class="text-center blue-td" width="20%">{{ avgSem1 }}</td>
                                  <td class="text-center" width="20%">{{ date[index] }}</td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
               </div>

               <div v-for="semester in semester2" class="sem-2 bg-light mb-5">
                   <h6 v-if="semester == '2'" class="text-center pt-5">Semester 2</h6>
                   <div v-if="semester == '2'" class="teacher_name">
                       <div class="col">
                           <p class="text-primary text-center"><b>{{ teacher + ' - Teacher' }}</b></p>
                       </div>
                   </div>
                   <div class="table-container2">
                       <table v-if="semester == '2'" class="table table-bordered">
                           <thead>
                               <tr>
                                   <th class="text-center yellow-tr">Subject</th>
                                   <th class="text-center green-tr">Rating</th>
                                   <th class="text-center blue-tr">Average</th>
                                   <th class="text-center">Date</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr v-for="(myGrade, index) in myGradesSem2">
                                   <td class="text-center text-danger yellow-td" width="40%">{{ subjects[0].name }}</td>
                                   <td class="text-center green-td" width="20%">{{ myGrade.rating }}</td>
                                   <td class="text-center blue-td" width="20%">{{ avgSem2 }}</td>
                                   <td class="text-center" width="20%">{{ date[index] }}</td>
                               </tr>
                           </tbody>
                       </table>
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
            avgSem1: {},
            avgSem2: {},
            className: {},
            date: {},
            myGradesSem1: {},
            myGradesSem2: {},
            semester1: {},
            semester2: {},
            subjects: {},
            teacher: {},
            switchFlashStyle: '',
            alerts: [],
            message: {
                warningText: '',
            },
            flashStyleWarning: {
               'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'absolute',
                    'top': '250px',
                    'left': '41.7%',
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
        getSourcesSemester1(id) {
            axios.get('/pupil/my-grades/' + id).then(response => {
                this.avgSem1 = response.data.avgSem1;
                this.className = response.data.className;
                this.date = response.data.date;
                this.myGradesSem1 = response.data.myGradesSem1;
                this.semester1 = response.data.semester1;
                this.subjects = response.data.subjects;
                this.teacher = response.data.teacher;
                this.showWarning(response.data.message);
            })
        },
        getSourcesSemester2(id) {
            axios.get('/pupil/my-grades/' + id).then(response => {
                this.avgSem2 = response.data.avgSem2;
                this.className = response.data.className;
                this.date = response.data.date;
                this.myGradesSem2 = response.data.myGradesSem2;
                this.semester2 = response.data.semester2;
                this.subjects = response.data.subjects;
                this.teacher = response.data.teacher;
                this.showWarning(response.data.message);
            })
        },
        showWarning(message) {
            if (this.myGradesSem1 === undefined || this.myGradesSem2 === undefined) {
                this.alerts.push(this.message.warningText = message);
                this.switchFlashStyle = this.flashStyleWarning.show;
            }
        },
    },
    mounted() {
        let id = window.location.href.split('/').pop();
        this.getSourcesSemester1(id);
        this.getSourcesSemester2(id);
    }
}
</script>

<style scoped>

   .grades-container {
       width: 100%;
       display: block;
   }
   .table-container1 {
       display: block;
       padding-top: 2px;
   }
   .table-container2 {
       position: relative;
       top: -9px;
       display: block;
       padding-top: 2px;
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
       padding-left: 2px;
       padding-right: 2px;
       width: 100%;
       display: block;
       border: 1px solid #efeff2;
   }
   .sem-2 {
       padding-left: 2px;
       padding-right: 2px;
       width: 100%;
       display: block;
       border: 1px solid #efeff2;
   }
   .header-text {
       color: #8f8f8f;
   }
   .flash-container {
       display: none;
   }
   .flash-container p {
       position: relative;
       top: 4px;
   }
   .teacher_name {
       display: block;
   }
   .error-explode p {
       padding-top: 2px;
   }

</style>

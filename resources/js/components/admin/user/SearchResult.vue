<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5">
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Id</th>
                            <th class="text-center text-white">Name</th>
                            <th class="text-center text-white">Email</th>
                            <th class="text-center text-white">Phone</th>
                            <th class="text-center text-white">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(result, i) in resultsData">
                            <td class="text-center pt-3">{{ result.id }}</td>
                            <td class="text-center pt-3">{{ result.name }}</td>
                            <td class="text-center pt-3">{{ result.email }}</td>
                            <td class="text-center pt-3">{{ result.phone }}</td>
                            <td class="text-center pt-3">{{ role[i].name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['resultsData', 'role'],
}
</script>

<style scoped>


</style>
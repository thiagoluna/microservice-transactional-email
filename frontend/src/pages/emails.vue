<template>
    <div class="container">
        <div class="table-responsive col-md-12">
            <table class="table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Email To</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Service</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(email, index ) in emails.data" :key="index">
                    <td>{{email.id}}</td>
                    <td>{{email.email}}</td>
                    <td>{{email.name}}</td>
                    <td>{{email.status}}</td>
                    <td>{{email.service}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "emails",
    mounted() {
       this.getEmails()
    },
    computed: {
        emails () {
            return this.$store.state.Emails.items
        }
    },
    methods: {
        getEmails () {
            this.$store.dispatch("getEmails")
                .catch(error => {
                    this.$vToastify.error("Error to Load Emails", 'Ops');
                })
        }
    }
}
</script>

<style scoped>

</style>
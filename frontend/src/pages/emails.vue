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
        <div class="col-12 mt-3 d-flex justify-content-center">
            <paginate :pagination="emails" @paginate="getEmails"></paginate>
        </div>
    </div>
</template>

<script>
import Pagination from "../layouts/Pagination";

export default {
    name: "emails",
    mounted() {
       this.getEmails()
    },
    computed: {
        emails () {
            return this.$store.state.Emails.items.data
        }
    },
    params () {
      return {
          page: this.emails.current_page
      }
    },
    methods: {
        getEmails (page = 1) {
            this.$store.dispatch("getEmails", {...this.params, page})
                .catch(error => {
                    this.$vToastify.error("Error to Load Emails", 'Ops');
                })
        }
    },
    components: {
        paginate: Pagination
    }
}
</script>

<style scoped>
.btn-primary, .btn-primary:visited {
    background-color: #ff8000 !important;
    border-color: #ff8000 !important;
}

.btn-primary:hover, .btn-primary:active {
    background-color: #cd761f !important;
    border-color: #cd761f !important;
}
</style>
<template>
    <div class="container">
        <div>
            <div class="mt-3">
                Please, fill in the form below with the Recipient's info.
            </div>
            <form class="row g-3" @submit.prevent="sendEmailTo">
                <div :class="['col-md-6', {'has-error': errors.email}]">
                    <label for="email" class="form-label mt-3" >Email To*</label>
                    <div class="text-danger" v-if="errors.email">
                        {{ errors.email[0]}}
                    </div>
                    <input type="email" class="form-control" id="email" v-model="formData.email">
                </div>
                <div :class="['col-md-6', {'has-error': errors.name}]">
                    <label for="name" class="form-label mt-3">Name *</label>
                    <div v-if="errors.name" class="text-danger">
                        {{ errors.name[0]}}
                    </div>
                    <input type="text" class="form-control" id="name" v-model="formData.name">
                </div>
                <div :class="['col-12', {'has-error': errors.subject}]">
                    <label for="subject" class="form-label mt-3">Subject *</label>
                    <div v-if="errors.subject" class="text-danger">
                        {{ errors.subject[0]}}
                    </div>
                    <input type="text" class="form-control" id="subject" placeholder="Enter the Subject" v-model="formData.subject">
                </div>
                <div :class="['col-12', {'has-error': errors.content}]">
                    <label for="content" class="form-label mt-3">Message *</label>
                    <div v-if="errors.content" class="text-danger">
                        {{ errors.content[0]}}
                    </div>
                    <input type="text" class="form-control" id="content" placeholder="Enter text/plain or <h1>text/html</h1>" v-model="formData.content">
                </div>
                <div class="col-12">
                    <div class="mt-3">
                        * Required Fields
                    </div>
                </div>
                <div class="col-12 mt-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary"  :disabled="loading">
                        <span v-if="loading">Sending....</span>
                        <span v-else> Send Email</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapActions } from  'vuex'

export default {
    name: "home",
    data () {
        return  {
            loading: false,
            formData: {
              name: '',
              email: '',
              subject: '',
              content: ''
            },
            errors: {
                name: '',
                email: '',
                subject: '',
                content: ''
            }
        }
    },
    methods: {
        ...mapActions([
           'sendEmail'
        ]),
        sendEmailTo () {
            this.loading = true
            this.sendEmail(this.formData)
                .then(response => {
                    this.errors = {}
                    this.formData = {}
                    this.$vToastify.success("Email Sent to Queue", 'Yes!')
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                        this.$vToastify.error("Incorrect Values. Verify again", 'Invalid Value.')
                        return
                    }
                    this.$vToastify.error("Failed to Send the Email", 'Ops')
                })
                .finally(() => this.loading = false)
        }
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

    .has-error { color: red; }
    .has-error input { border: 1px solid red; }
</style>
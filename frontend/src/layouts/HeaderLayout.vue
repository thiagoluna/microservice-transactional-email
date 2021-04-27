<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="../assets/images/logo.png" alt="Just EAT Takeaway" class="img">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link :to="{name: 'home'}" class="nav-link">Send Email</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'emails'}" class="nav-link">List Emails</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    name: "HeaderLayout"
}
</script>

<style scoped>
    .nav-link {
        color: #a5a6a9;
    }
    .navbar-nav .nav-link {
        color: #a5a6a9;
    }
    .navbar-light .navbar-nav .nav-link:focus, .navbar-light .navbar-nav .nav-link:hover {
        color: #ff8000;
    }
    .img {
        width: 100%;
    }
    .navbar-brand {
        width: 150px;
    }

</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card border-primary text-white bg-success mb-3" style="max-width: 18rem;">
                    <h6 class="card-header">Pictures Stats: </h6>
                    <div class="card-body">
                        <div v-for="pic in pics">
                            <div class="card-text">Download: <span class="badge badge-warning float-right">{{pic.download.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">Processed: <span class="badge badge-warning float-right">{{pic.processed.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">NotProcessed: <span class="badge badge-warning float-right"> {{pic.notprocessed.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">SmallSize: <span class="badge badge-warning float-right"> {{pic.smallsize.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">Clarifai: <span class="badge badge-warning float-right"> {{pic.clarifai.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">Ignored: <span class="badge badge-warning float-right"> {{pic.ignored.toLocaleString()}}</span>
                            </div>
                            <div class="card-text">Total: <span
                                    class="badge badge-warning float-right"> {{pic.total.toLocaleString()}}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-primary text-white bg-success mb-3" style="max-width: 18rem;">
                    <h6 class="card-header">Recognition Stats: </h6>
                    <div class="card-body">
                        <div v-for="recognition in recognitions">
                            <div>Selected: <span class="badge badge-warning float-right">{{recognition.selected.toLocaleString()}}</span>
                            </div>
                            <div>Manual: <span class="badge badge-warning float-right">{{recognition.manual.toLocaleString()}}</span>
                            </div>
                            <div>Ignored: <span class="badge badge-warning float-right">{{recognition.ignored.toLocaleString()}}</span>
                            </div>
                            <div>Total: <span class="badge badge-warning float-right"> {{recognition.total.toLocaleString()}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-primary text-white bg-success mb-3" style="max-width: 18rem;">
                    <h6 class="card-header">Users Stat: </h6>
                    <div class="card-body">
                        <div v-for="user in users">
                            <div>Selected: <span class="badge badge-warning float-right"> {{user.selected.toLocaleString()}}</span>
                            </div>
                            <div>GeoUsers: <span class="badge badge-warning float-right"> {{user.geousers.toLocaleString()}}</span>
                            </div>
                            <div>Processed: <span class="badge badge-warning float-right"> {{user.processed.toLocaleString()}}</span>
                            </div>
                            <div>Ignored: <span class="badge badge-warning float-right"> {{user.ignored.toLocaleString()}}</span>
                            </div>
                            <div>Total: <span
                                    class="badge badge-warning float-right"> {{user.total.toLocaleString()}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card border-primary text-white bg-success mb-3" style="max-width: 18rem;">
                    <h6 class="card-header">Groups Stat</h6>
                    <div class="card-body">
                        <div v-for="group in groups">
                            <div>Processed: <span class="badge badge-warning float-right"> {{group.done.toLocaleString()}}</span>
                            </div>
                            <div>Ignored: <span class="badge badge-warning float-right"> {{group.closed.toLocaleString()}}</span>
                            </div>
                            <div>Total: <span
                                    class="badge badge-warning float-right"> {{group.total.toLocaleString()}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-primary text-white bg-success mb-3" style="max-width: 18rem;">
                    <h6 class="card-header">Disc Stat: </h6>
                    <div class="card-body">
                        <div v-for="storage in storages">
                            <div>Pictures: <span class="badge badge-warning float-right"> {{storage.picturesize.toLocaleString()}}</span>
                            </div>
                            <div>Database: <span class="badge badge-warning float-right"> {{storage.dbsize.toLocaleString()}}</span>
                            </div>
                        </div>
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
                users: [],
                user: {
                    selected: 0,
                    geousers: 0,
                    processed: 0,
                    ignored: 0,
                    total: 0,
                },
                groups: [],
                group: {
                    done: 0,
                    closed: 0,
                    total: 0,
                },
                storages: [],
                storage: {
                    picturesize: 0,
                    dbsize: 0,
                },
                recognitions: [],
                recognition: {
                    manual: 0,
                    selected: 0,
                    ignored: 0,
                    total: 0,
                },
                pics: [],
                pic: {
                    total: 0,
                    download: 0,
                    processed: 0,
                    notprocessed: 0,
                    clarifai: 0,
                    ignored: 0,
                    smallsize: 0,
                }
            }
        },
        mounted() {
            this.getStorage();
            this.getRecognitions();
            this.getGroups();
            this.getUsers();
            this.getPics();
        },
        methods: {
            getPics() {
                axios.get('/api/v1/quickstats/pictures').then(responce => (
                    this.pics = responce.data
                ));
            },
            getRecognitions() {
                axios.get('/api/v1/quickstats/recognition').then(responce => (
                    this.recognitions = responce.data
                ));
            },
            getUsers() {
                axios.get('/api/v1/quickstats/users').then(responce => (
                    this.users = responce.data
                ));
            },
            getGroups() {
                axios.get('/api/v1/quickstats/groups').then(responce => (
                    this.groups = responce.data
                ));
            },
            getStorage() {
                axios.get('/api/v1/quickstats/storage').then(responce => (
                    this.storages = responce.data
                ));
            },
        }
    }
</script>

<style>

</style>
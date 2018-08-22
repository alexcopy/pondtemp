<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <h5>Today results</h5>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>cam name</th>
                        <th>q-ty</th>
                        <th>size</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td class="total"><b>Total</b></td>
                        <td><b><span class="alert-success badge">{{cams.stats.filescount.toLocaleString()}}</span></b>
                        </td>
                        <td><b><span class="alert-success">{{cams.stats.alldirs.toLocaleString()}}</span></b></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <tr v-for="cam in cams.data">
                        <td><a v-bind:href="'/allfiles/details?q=showtoday&folder='+ cam.camname+'&limit=500'">{{cam.camname.toLocaleString()}}</a>
                        </td>
                        <td><span class="alert-info badge">{{cam.filescount.toLocaleString()}}</span></td>
                        <td>{{cam.size}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-5">
                <h5>Status</h5>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>cam name</th>
                        <th>is OK</th>
                        <th>last alarm</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="status in statuses">
                        <td>{{status.camname.toLocaleString()}}</td>
                        <td v-if="status.isOK===true"><b><span class="alert-success badge">OK</span></b></td>
                        <td v-else><b><span class="alert-danger badge">error</span> </b></td>
                        <td><span class="text-nowrap">{{status.lastalarm.toLocaleString()}}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-sm-3">
                <h5>All Dirs Result</h5>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>cam name</th>
                        <th>q-ty</th>
                        <th>size</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td class="total"><b>Total</b></td>
                        <td><b><span class="alert-success badge">{{totalstats.stats.dirscount}}</span></b>
                        </td>
                        <td><b><span class="alert-success">{{totalstats.stats.alldirs.toLocaleString()}}</span></b></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <tr v-for="totalstat in totalstats.data">
                        <td><a v-bind:href="'/allfiles/details?q=showfolders&folder='+ totalstat.camname+'&limit=500'">{{totalstat.camname.toLocaleString()}}</a>
                        </td>
                        <td><span class="alert-info badge">{{totalstat.dirs.toLocaleString()}}</span></td>
                        <td>{{totalstat.size.toLocaleString()}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                cams: [],
                cam: {
                    filescount: 0,
                    dirs: 0,
                    lastchanged: 0,
                    size: 0,
                    camname: 0,
                    stats: {
                        alldirs: 0,
                        filescount: 0
                    }
                },
                totalstats: {
                    stats: {
                        dirscount: 0,
                        alldirs: 0
                    }
                },
                totalstat: {
                    camname: '',
                    dirs: 0,
                    size: 0,
                    stats: {
                        dirscount: 0,
                        alldirs: 0
                    }
                },
                statuses: [],
                status: {
                    camname: '',
                    isOK: 0,
                    lastalarm: ''
                }
            }
        },
        async mounted() {
            this.getTodayResults(),
                this.todayStats(),
                this.getTotalResults()
        },
        methods: {
            getTodayResults() {
                axios.get('/api/v3/stats/today').then(responce => (
                    this.cams = responce.data,
                        this.todayStats()
                ));
            },
            getTotalResults() {
                axios.get('/api/v3/stats/total').then(responce => (
                    this.totalstats = responce.data
                ));
            },
            todayStats() {
                let cams = this.cams.data;
                for (var i in cams) {
                    this.statuses.push({
                        camname: cams[i].camname,
                        isOK: parseInt(cams[i].isOK) < 7200 ? true : false,
                        lastalarm: cams[i].lastchanged
                    })
                }
            }
        }
    }
</script>

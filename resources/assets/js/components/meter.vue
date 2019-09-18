<template>
    <div class="container">

        <div>
            <button type="button" class="btn btn-success" data-toggle="modal"
                    data-target="#addMeter">
                Add New Readings
            </button>
        </div>

        <div class="row">
            <div class="col-sm-3 pull-left">
                <h5 class="text-danger">weekly speed :<i> {{ stats.weekStats['hourly'] }} </i> L/hour</h5>
                <h5 class="text-info">weekly speed:<i> {{ stats.weekStats['daily'] }} </i> L/day</h5>
                <h5 class="text-info">weekly used :<i> {{(stats.weekStats['used']/1000).toFixed(0) }}</i> m3</h5>
            </div>

            <div class="col-sm-3 pull-left">
                <h5 class="text-danger">monthly speed :<i> {{ stats.monthStats['hourly'] }} </i> L/hour</h5>
                <h5 class="text-info">monthly speed:<i> {{ stats.monthStats['daily'] }} </i> L/day</h5>
                <h5 class="text-info">monthly used :<i> {{(stats.monthStats['used']/1000).toFixed(0)}}</i> m3</h5>
            </div>
            <div class="col-sm-3 pull-left">
                <h5 class="text-danger">annual speed :<i> {{ stats.annualStats['hourly'] }} </i> L/hour</h5>
                <h5 class="text-info">annual speed:<i> {{ stats.annualStats['daily'] }} </i> L/day</h5>
                <h5 class="text-info">annual used ({{(stats.annualStats['interval']/86400).toFixed(0)}}
                    days):<i> {{(stats.annualStats['used']/1000).toFixed(0) }}</i> m3</h5>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>read</th>
                <th>used</th>
                <th>speed</th>
                <th>MSG</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="metersRow in metersData.data" :key="metersRow.id" :data-dif="metersRow.id">
                <td>{{metersRow.readings}}</td>
                <td>{{ metersDiffDataData[metersRow.id].diff}}</td>
                <td><span class="text-danger">{{metersDiffDataData[metersRow.id].perHour}} l/h</span></td>
                <td><span class="limtext_100">{{metersRow.message}}</span></td>
                <td>{{metersDiffDataData[metersRow.id].time}}</td>
                <td>
                    <button class="btn btn-xs btn-info">
                        <i class="glyphicon glyphicon-pencil"></i>
                    </button>
                    <button class="btn btn-xs btn-danger danger">
                        <i class="fa fa-thumbs-down"></i>
                    </button>
                    <button class="btn btn-danger btn-xs danger">
                        <i class="fa fa-times "></i>
                    </button>
                </td>
            </tr>

            </tbody>
        </table>
        <div>
            <pagination :data="metersData" @pagination-change-page="getMetersData"></pagination>
        </div>

        <div>
            <div class="modal fade" id="addMeter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLabel">
                                <slot name="header"></slot>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <slot></slot>
                            <form @submit.prevent="submit">

                                <div class="form-group">
                                    <label>Meter ID:</label>
                                    <select class='form-control' v-model='fields.meter_id' @change='getMeters()'
                                            name="meter_id" id="meter_id">
                                        <option value='0'>Select meter ID</option>
                                        <option v-for='data in meters'
                                                :selected='data.id === 1'
                                                :value='data.id'>{{ data.deviceName }}
                                        </option>
                                    </select>
                                    <div v-if="errors && errors.meter_id" class="text-danger">{{ errors.meter_id[0] }}
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="readings">Readings</label>
                                    <input type="text" class="form-control" name="readings" id="readings"
                                           v-model="fields.readings" autocomplete="off"/>
                                    <div v-if="errors && errors.readings" class="text-danger">{{ errors.readings[0] }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5"
                                              v-model="fields.message"></textarea>
                                    <div v-if="errors && errors.message" class="text-danger">{{ errors.message[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div slot="modal-footer" class="pull-right">
                            <slot name="footer" class="pull-right">
                                <p slot="footer">
                                    <button type="submit" class="btn btn-primary" @click="submit">Save
                                        changes
                                    </button>
                                    <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                                </p>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
    import FormMixin from '../FormMixin';

    export default {
        mixins: [FormMixin],
        data() {
            return {
                'action': '/pond/meters/submit',
            }
        },
    }
</script>
<style>
    .limtext {
        display: inline-block;
        width: 70px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .limtext_100 {
        display: inline-block;
        width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
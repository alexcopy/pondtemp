<template>
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
                            <select class='form-control' v-model='fields.meter_id' @change='getMeters()' name="meter_id" id="meter_id">
                                <option value='0' >Select meter ID</option>
                                <option v-for='data in meters' :value='data.id'>{{ data.deviceName }}</option>
                            </select>
                            <div v-if="errors && errors.meter_id" class="text-danger">{{ errors.meter_id[0] }}</div>
                        </div>

                        <div class="form-group">
                            <label for="readings">Readings</label>
                            <input type="text" class="form-control" name="readings" id="readings"
                                   v-model="fields.readings" autocomplete="off" />
                            <div v-if="errors && errors.readings" class="text-danger">{{ errors.readings[0] }}</div>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5"
                                      v-model="fields.message"></textarea>
                            <div v-if="errors && errors.message" class="text-danger">{{ errors.message[0] }}</div>
                        </div>
                    </form>
                </div>
                <div slot="modal-footer" class="pull-right">
                <slot name="footer" class="pull-right">
                    <p slot="footer">
                        <button  type="submit" class="btn btn-primary"  @click="submit" >Save changes</button>
                        <button type="button" class="btn  btn-danger" data-dismiss="modal">Close</button>
                    </p>
                </slot>
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
        }
    }
</script>